<?php

namespace App\Jobs;

use App\Actions\ExtractAudio;
use App\Actions\{GenerateSubtitles, GenerateChapters, GenerateSummary};
use App\Actions\TranslateSubtitles;
use App\Enums\Status;
use App\Models\Process;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessVideo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct(public Process $process)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(Pipeline::class)
            ->send($this->process)
            ->through([
                ExtractAudio::class,
                GenerateSubtitles::class,
                TranslateSubtitles::class,
                GenerateChapters::class,
                GenerateSummary::class,
            ])
            ->then(function (Process $process) {
                $process->update([
                    'status' => Status::COMPLETE,
                ]);
            });
    }
}
