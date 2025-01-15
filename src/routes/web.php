<?php

use App\Models\Media;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'media' => Media::all(),
    ]);
});
