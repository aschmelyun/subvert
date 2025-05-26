<?php

namespace App\Actions;
use App\Enums\Status;
use App\Models\Process;
use App\Services\AiService;
use OpenAI\Laravel\Facades\OpenAI;

class GenerateSubtitles
    public function __construct(protected AiService $ai) {}
{
    public function handle(Process $process, \Closure $next)
    {
        $process->update([
            'status' => Status::PROCESSING_SUBTITLES
        ]);

        try {
            $transcript = OpenAI::audio()->transcribe([
                'model' => 'whisper-1',
                'file' => fopen(storage_path("app/audio/{$process->id}.mp3"), 'r'),
                'response_format' => 'vtt'
            ]);

            $process->update([
                'transcript' => $transcript->text
            ]);
        try {
            $transcript = $this->ai->audioTranscribe([
                'model' => 'whisper-1',
                'file' => fopen(storage_path("app/audio/{$process->id}.mp3"), 'r'),
                'response_format' => 'vtt',
            ]);

            $process->update([
                'transcript' => is_object($transcript) ? $transcript->text : ($transcript['text'] ?? ''),
            ]);
        } catch (\Exception $e) {
            ]);

            return;
        }

        return $next($process);
    }
}
