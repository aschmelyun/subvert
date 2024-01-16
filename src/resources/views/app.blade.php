<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub&veebar;ert</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.maxFilesize = "{{ ini_get('upload_max_filesize') }}";
    </script>
</head>
<body>
    <div class="container mx-auto py-4 antialiased">
        <div class="flex items-center justify-between">
            <a href="/" class="text-xl font-semibold hover:text-purple-500 transition-colors duration-200">Sub<span class="underline">v</span>ert</a>
            <a href="https://github.com/aschmelyun/subvert" target="_blank" class="block text-sm font-medium text-white bg-black rounded-full px-4 py-2 hover:bg-purple-500 transition-colors duration-200" rel="noopener noreferrer">GitHub</a>
        </div>
    </div>
    @yield('content')
    <footer class="text-center mt-12 mb-8">
        <p class="text-sm text-gray-500">Crée en un weekend par <a href="https://twitter.com/aschmelyun" target="_blank" class="underline hover:text-gray-800 transition-colors duration-200" rel="noopener noreferrer">Andrew Schmelyun</a></p>
        <p class="text-sm text-gray-500">Traduit en français par <a href="https://github.com/PinkFloyd1213" target="_blank" class="underline hover:text-gray-800 transition-colors duration-200" rel="noopener noreferrer">PinkFloyd1213<a/></p>
        @if(env('APP_VERSION'))
            <p class="text-sm text-gray-300 mt-1">[Taille de fichier max: {{ ini_get('upload_max_filesize') }}] [v{{ env('APP_VERSION') }}]</p>
        @endif
    </footer>
</body>