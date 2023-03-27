@extends('app')
@section('content')
    <div class="max-w-3xl mx-auto my-12">
        <div class="text-center mb-8">
            <h1 class="text-2xl mb-4">Process <span class="font-semibold">{{ $process->id }}</span></h1>
            @if ($process->transcript)
                <button class="flex items-center mx-auto text-sm font-semibold text-white bg-black rounded-full px-4 py-2 hover:bg-purple-500 transition-colors duration-200" onclick="window.location.href = '/process/{{ $process->id }}/download'">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    Download VTT                  
                </button>
            @endif
        </div>
        @if ($process->summary)
            <h3 class="text-xl font-semibold text-center mb-2">Summary</h3>
            <p class="mb-12">{{ $process->summary }}</p>
        @endif
        @if ($process->chapters)
            <h3 class="text-xl font-semibold text-center mb-2">Chapters</h3>
            <div class="mb-12">
                @foreach(explode("\n", $process->chapters) as $chapter)
                    <p>{{ $chapter }}</p>
                @endforeach
            </div>
        @endif
        @if ($process->transcript)
            <h3 class="text-xl font-semibold text-center mb-2">Subtitles</h3>
            <div>
                @foreach($process->transcriptArray as $key => $subtitle)
                    <p class="{{ $key % 2 === 0 ? 'text-sm text-gray-500' : 'mb-4' }}">{{ $subtitle }}</p>
                @endforeach
            </div>
        @endif
    </div>
@endsection