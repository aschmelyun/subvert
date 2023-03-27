<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\ProcessVideo;
use App\Models\Process;

class InitiateProcessing extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Validate the request and create an action record
        $request->validate([
            'video' => 'required|file',
            'options' => 'required',
        ]);

        $process = Process::create([
            'id' => Str::uuid(),
            'options' => $request->options,
            'file' => $request->file('video')->store('video'),
        ]);

        // Create a job for processing the video and queue it
        ProcessVideo::dispatch($process);

        // Return the process to the client
        // The client can then poll the /api/process endpoint for status
        return $process;
    }
}
