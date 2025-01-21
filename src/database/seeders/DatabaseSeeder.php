<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workflow;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generic Chapters workflow
        $workflow = Workflow::create([
            'name' => 'Chapters',
            'description' => 'Creates chapters for the video or audio based on context switching.',
            'color' => 'rose',
        ]);

        $workflow->steps()->create([
            'name' => 'Chapterize',
            'prompt' => "You are a video editor. You will be given subtitles of a video. You need to summarize the video as a list of {{ count }} concise chapters, no more than a short sentence each. Each chapter should be prefixed with a single timestamp relevant to the starting position in the video. Provide back only the list of chapters, nothing before and nothing after.",
            'config' => [
                'count' => 5,
            ],
            'order' => 1
        ]);

        // Generic Video Description workflow
        $workflow = Workflow::create([
            'name' => 'Video Description',
            'description' => 'Creates text associated with the video or audio that you might see on a YouTube description.',
            'color' => 'blue',
        ]);

        $workflow->steps()->create([
            'name' => 'Make Description',
            'prompt' => "You are a video editor. You will be given subtitles of a video. You need to summarize the video in a concise manner, as a single paragraph, and no more than {{ sentences }} sentences. Provide back only the summary text, nothing before and nothing after.",
            'config' => [
                'sentences' => 4,
            ],
            'order' => 1
        ]);
    }
}
