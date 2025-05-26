<?php

namespace App\Actions;

use App\Enums\Status;
use App\Models\Process;
use App\Services\AiService;

class GenerateSummary
    public function __construct(protected AiService $ai) {}
{
    public function handle(Process $process, \Closure $next)
    {
        $process->update([
            'status' => Status::PROCESSING_SUMMARY
        ]);

        $option = filter_var($process->options['summary'], FILTER_VALIDATE_BOOLEAN);
        if (!$option) {
            return $next($process);
        }

        try {
            $completedSummaryChunks = [];

            foreach($process->transcriptChunks as $chunk) {
                $completedSummaryChunks[] = $this->getSummary($chunk);
    private function getSummary($subtitles)
    {
        $response = $this->ai->chat([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.2,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a video editor. You will be given subtitles of a video. You need to summarize the video in a concise manner, as a single paragraph, and no more than 5 sentences. Provide back only the summary text, nothing before and nothing after.'],
                ['role' => 'user', 'content' => implode("\n", $subtitles)],
    private function getCompiledSummary($summaryChunks)
    {
        $response = $this->ai->chat([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.2,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a video editor. You will be given a large summary of a video. You need to condense this summary down in a concise manner, as a single paragraph, and no more than 5 sentences. Provide back only the summary text, nothing before and nothing after.'],
                ['role' => 'user', 'content' => implode(' ', $summaryChunks)],
            ],
        ]);

        $compiled = '';
        foreach (($response->choices ?? $response['choices']) as $choice) {
            $compiled .= $choice->message->content ?? $choice['message']['content'];
        }

        return $compiled;
    }
        return $completed;
    }
        } catch (\Exception $e) {
            $process->update([
                'status' => Status::ERRORED,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        return $next($process);
    }

    private function getSummary($subtitles)
    {
        $summary = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.2,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a video editor. You will be given subtitles of a video. You need to summarize the video in a concise manner, as a single paragraph, and no more than 5 sentences. Provide back only the summary text, nothing before and nothing after.'
                ],
                [
                    'role' => 'user',
                    'content' => implode("\n", $subtitles)
                ]
            ]
        ]);

        $completedSummary = '';
        foreach($summary->choices as $choice) {
            $completedSummary .= $choice->message->content;
        }

        return $completedSummary;
    }

    private function getCompiledSummary($summaryChunks)
    {
        $summary = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.2,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a video editor. You will be given a large summary of a video. You need to condense this summary down in a concise manner, as a single paragraph, and no more than 5 sentences. Provide back only the summary text, nothing before and nothing after.'
                ],
                [
                    'role' => 'user',
                    'content' => implode(' ', $summaryChunks)
                ]
            ]
        ]);

        $compiledSummary = '';
        foreach($summary->choices as $choice) {
            $compiledSummary .= $choice->message->content;
        }

        return $compiledSummary;
    }
}
