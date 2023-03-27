<?php

namespace App\Actions;

use App\Enums\Status;
use App\Models\Process;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Audio;

class ExtractAudio
{
    public function handle(Process $process, \Closure $next)
    {
        $process->update([
            'status' => Status::EXTRACTING_AUDIO
        ]);

        try {
            $ffmpeg = FFMpeg::create();
            $video = $ffmpeg->open(storage_path("app/{$process->file}"));

            $format = new Audio\Mp3();
            $video->save($format, storage_path("app/audio/{$process->id}.mp3"));
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