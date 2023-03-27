<?php

use App\Http\Controllers\InitiateProcessing;
use App\Models\Process;
use Illuminate\Support\Facades\Route;

Route::post('/process', InitiateProcessing::class);
Route::get('/process/{process}', function (Process $process) {
    $process->message = $process->status->message();
    
    return $process;
});
