<?php

namespace App\Actions;

use App\Enums\Status;
use App\Models\Process;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Opt-in alternative to the OpenAI pipeline.
 *
 * Where the default flow extracts audio, transcribes with Whisper, then makes
 * several ChatGPT calls for chapters and a summary, this action sends the video
 * straight to TwelveLabs' Pegasus model and gets subtitles, a summary, and
 * chapters back from a single video-native analysis call.
 *
 * It only runs when the `twelvelabs` provider is selected (TWELVELABS_API_KEY
 * set + AI_PROVIDER=twelvelabs, or the per-request `provider` option). When it
 * runs it fully populates the process and marks it complete, so the downstream
 * OpenAI actions are skipped. Otherwise it is a transparent pass-through.
 */
class AnalyzeWithTwelveLabs
{
    public function handle(Process $process, \Closure $next)
    {
        if (! $this->shouldRun($process)) {
            return $next($process);
        }

        try {
            $assetId = $this->uploadAsset($process);

            $analysis = $this->analyze($process, $assetId);

            $process->update([
                'transcript' => $analysis['subtitles'] ?? null,
                'summary' => $this->wants($process, 'summary') ? ($analysis['summary'] ?? null) : null,
                'chapters' => $this->wants($process, 'chapters') ? $this->formatChapters($analysis['chapters'] ?? []) : null,
                'status' => Status::COMPLETE,
            ]);
        } catch (\Throwable $e) {
            $process->update([
                'status' => Status::ERRORED,
                'error' => $e->getMessage(),
            ]);
        }

        // This provider produces everything in one call, so we deliberately do
        // not pass the process down to the OpenAI actions.
        return;
    }

    private function shouldRun(Process $process): bool
    {
        if (! config('services.twelvelabs.api_key')) {
            return false;
        }

        $provider = $process->options['provider'] ?? env('AI_PROVIDER', 'openai');

        return $provider === 'twelvelabs';
    }

    private function wants(Process $process, string $option): bool
    {
        return filter_var($process->options[$option] ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(config('services.twelvelabs.base_url'))
            ->withHeaders(['x-api-key' => config('services.twelvelabs.api_key')])
            ->timeout(600);
    }

    /**
     * Upload the stored video as a TwelveLabs asset and wait for it to be ready.
     */
    private function uploadAsset(Process $process): string
    {
        $process->update(['status' => Status::EXTRACTING_AUDIO]);

        $path = storage_path("app/{$process->file}");

        $response = $this->client()
            ->attach('file', fopen($path, 'r'), basename($path))
            ->post('/assets', ['method' => 'direct'])
            ->throw()
            ->json();

        $assetId = $response['_id'] ?? null;
        if (! $assetId) {
            throw new \RuntimeException('TwelveLabs did not return an asset id.');
        }

        $deadline = now()->addMinutes(10);
        do {
            $asset = $this->client()->get("/assets/{$assetId}")->throw()->json();
            $status = $asset['status'] ?? 'processing';

            if ($status === 'ready') {
                return $assetId;
            }

            if ($status === 'failed') {
                throw new \RuntimeException('TwelveLabs failed to process the uploaded asset.');
            }

            sleep(5);
        } while (now()->lessThan($deadline));

        throw new \RuntimeException('Timed out waiting for the TwelveLabs asset to become ready.');
    }

    /**
     * Run a single Pegasus analysis that returns subtitles, a summary, and
     * chapters as structured JSON.
     */
    private function analyze(Process $process, string $assetId): array
    {
        $process->update(['status' => Status::PROCESSING_SUBTITLES]);

        $language = ($process->options['language'] ?? 'default');
        $language = $language === 'default' ? 'the original spoken language' : $language;
        $chaptersAmount = (int) ($process->options['chapters_amount'] ?? 5);

        $prompt = "Watch the video and return: (1) word-for-word subtitles in {$language} as an array "
            . "of cues, each with start and end timestamps in seconds and the spoken text; "
            . "(2) a concise summary of no more than 5 sentences that would fit a video description; "
            . "(3) exactly {$chaptersAmount} chapters spread evenly across the video, each with a start "
            . "timestamp in seconds and a short title.";

        $schema = [
            'type' => 'object',
            'properties' => [
                'subtitles' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'start' => ['type' => 'number'],
                            'end' => ['type' => 'number'],
                            'text' => ['type' => 'string'],
                        ],
                        'required' => ['start', 'end', 'text'],
                    ],
                ],
                'summary' => ['type' => 'string'],
                'chapters' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'start' => ['type' => 'number'],
                            'title' => ['type' => 'string'],
                        ],
                        'required' => ['start', 'title'],
                    ],
                ],
            ],
            'required' => ['subtitles', 'summary', 'chapters'],
        ];

        $response = $this->client()->post('/analyze', [
            'model_name' => config('services.twelvelabs.model'),
            'video' => ['type' => 'asset_id', 'asset_id' => $assetId],
            'prompt' => $prompt,
            'temperature' => 0.2,
            'max_tokens' => 4096,
            'stream' => false,
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => ['name' => 'subvert_analysis', 'schema' => $schema],
            ],
        ])->throw()->json();

        $data = json_decode($response['data'] ?? '{}', true);

        return [
            'subtitles' => $this->toVtt($data['subtitles'] ?? []),
            'summary' => $data['summary'] ?? null,
            'chapters' => $data['chapters'] ?? [],
        ];
    }

    /**
     * Render Pegasus subtitle cues as a WebVTT document (the format the rest of
     * Subvert already stores and downloads).
     */
    private function toVtt(array $cues): string
    {
        $lines = ['WEBVTT', ''];

        foreach ($cues as $cue) {
            if (! isset($cue['start'], $cue['end'], $cue['text'])) {
                continue;
            }

            $lines[] = $this->timestamp($cue['start']) . ' --> ' . $this->timestamp($cue['end']);
            $lines[] = trim($cue['text']);
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    private function timestamp(float $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds - $hours * 3600) / 60);
        $secs = $seconds - $hours * 3600 - $minutes * 60;

        return sprintf('%02d:%02d:%06.3f', $hours, $minutes, $secs);
    }

    /**
     * Match the plain-text "timestamp + title" chapter format used by the
     * OpenAI chapters action.
     */
    private function formatChapters(array $chapters): string
    {
        $lines = [];

        foreach ($chapters as $chapter) {
            if (! isset($chapter['start'], $chapter['title'])) {
                continue;
            }

            $lines[] = $this->timestamp($chapter['start']) . ' ' . trim($chapter['title']);
        }

        return implode("\n", $lines);
    }
}
