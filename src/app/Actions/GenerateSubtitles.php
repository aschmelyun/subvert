<?php

namespace App\Actions;

use App\Enums\Status;
use App\Models\Process;
use OpenAI\Laravel\Facades\OpenAI;

class GenerateSubtitles
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
        } catch (\Exception $e) {
            $process->update([
                'status' => Status::ERRORED,
                'error' => $e->getMessage(),
            ]);

            return;
        }

        return $next($process);
    }
}