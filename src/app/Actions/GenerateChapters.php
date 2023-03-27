<?php

namespace App\Actions;

use App\Enums\Status;
use App\Models\Process;
use OpenAI\Laravel\Facades\OpenAI;

class GenerateChapters
{
    public function handle(Process $process, \Closure $next)
    {
        $process->update([
            'status' => Status::PROCESSING_CHAPTERS
        ]);

        $option = filter_var($process->options['chapters'], FILTER_VALIDATE_BOOLEAN);
        if (!$option) {
            return $next($process);
        }

        $chaptersAmount = $process->options['chapters_amount'];

        try {
            $completedChapterChunks = [];

            foreach($process->transcriptChunks as $chunk) {
                $completedChapterChunks[] = $this->getChapters($chunk, $chaptersAmount);
            }

            $completedChapters = $this->getCompiledChapters($completedChapterChunks, $chaptersAmount);

            $process->update([
                'chapters' => $completedChapters
            ]);
        } catch (\Exception $e) {
            $process->update([
                'status' => Status::ERRORED,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        return $next($process);
    }

    private function getChapters($subtitles, $chaptersAmount)
    {
        $chapters = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.2,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a video editor. You will be given subtitles of a video. You need to summarize the video as a list of {$chaptersAmount} concise chapters, no more than a short sentence each. Each chapter should be prefixed with a single timestamp relevant to the starting position in the video. Provide back only the list of chapters, nothing before and nothing after."
                ],
                [
                    'role' => 'user',
                    'content' => implode("\n", $subtitles)
                ]
            ]
        ]);

        $completedChapters = '';
        foreach($chapters->choices as $choice) {
            $completedChapters .= $choice->message->content;
        }

        return $completedChapters;
    }

    private function getCompiledChapters($chapterChunks, $chaptersAmount)
    {
        $chapters = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.2,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a video editor. You will be given a list of chapters for a video. You need to reduce this list down to just {$chaptersAmount} chapters, spread out evenly throughout the entire original list. Keep the relevant, singular, timestamp from the beginning of the original chapter. Provide back only the list of chapters, nothing before and nothing after."
                ],
                [
                    'role' => 'user',
                    'content' => implode(' ', $chapterChunks)
                ]
            ]
        ]);

        $completedChapters = '';
        foreach($chapters->choices as $choice) {
            $completedChapters .= $choice->message->content;
        }

        return $completedChapters;
    }
}