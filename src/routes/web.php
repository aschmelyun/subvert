<?php

use App\Models\Media;
use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'media' => Media::all(),
    ]);
})->name('dashboard');

Route::post('/process', function (Request $request) {
    $request->validate([
        'file' => 'nullable|file|required_without:link',
        'link' => 'nullable|url|required_without:file',
    ]);

    $media = Media::make([
        'title' => $request->hasFile('file') ? $request->file('file')->getClientOriginalName() : $request->input('link'),
        'description' => '',
        'type' => null,
    ]);

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $path = $file->store('media');
        $media->file_path = $path;
        $media->file_size = $file->getSize();

        $mimeType = $file->getMimeType();
        $media->mime_type = $mimeType;
        $media->type = str_starts_with($mimeType, 'video/') ? 'video' : (str_starts_with($mimeType, 'audio/') ? 'audio' : null);
    } elseif ($request->has('link')) {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
        $audioExtensions = ['mp3', 'wav', 'ogg', 'aac', 'm4a'];

        $extension = pathinfo(parse_url($request->input('link'), PHP_URL_PATH), PATHINFO_EXTENSION);
        if (! in_array($extension, [...$videoExtensions, ...$audioExtensions])) {
            return redirect()->back()->withErrors('Invalid link provided.', 'link');
        }

        $media->external_url = $request->input('link');
        $media->type = in_array($extension, $videoExtensions) ? 'video' : 'audio';
    }

    $media->save();

    return redirect()->route('media.show', $media->id);
});

Route::get('/media/{media}', function (Media $media) {
    return Inertia::render('Media', [
        'media' => $media,
    ]);
})->name('media.show');

Route::get('/workflows', function () {
    return Inertia::render('Workflows', [
        'workflows' => Workflow::all(),
    ]);
})->name('workflows.index');

Route::post('/workflows/create', function (Request $request) {
    $request->validate([
        'name' => 'required',
    ]);

    $workflow = Workflow::create([
        'name' => $request->name,
        'description' => '',
        'color' => Workflow::randomColor(),
    ]);

    return redirect()->route('workflows.show', $workflow->id);
})->name('workflows.store');

Route::get('/workflows/{workflow}', function (Workflow $workflow) {
    return Inertia::render('Workflow', [
        'workflow' => $workflow
    ]);
})->name('workflows.show');

Route::get('/phpinfo', function () {
    return phpinfo();
});
