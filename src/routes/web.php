<?php

use Illuminate\Support\Facades\Route;
use App\Models\Process;

Route::view('/', 'index');

Route::get('/process/{process}', function (Process $process) {
    return view('process', [
        'process' => $process,
    ]);
});

Route::get('/process/{process}/download', function (Process $process) {
    $filename = "subtitles-{$process->id}.vtt";

    return response($process->transcript, 200, [
        'Content-Type' => 'text/vtt',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
});
