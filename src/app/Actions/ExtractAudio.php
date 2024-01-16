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

        // If the file is an audio file already,
        // copy the file to the audio directory and go to the next action
        if (mime_content_type(storage_path("app/{$process->file}")) === 'audio/mpeg') {
            copy(storage_path("app/{$process->file}"), storage_path("app/audio/{$process->id}.mp3"));
            
            return $next($process);
        }

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