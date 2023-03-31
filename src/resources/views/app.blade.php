<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub&veebar;ert</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        <p class="text-sm text-gray-500">Thrown together in a weekend by <a href="https://twitter.com/aschmelyun" target="_blank" class="underline hover:text-gray-800 transition-colors duration-200" rel="noopener noreferrer">Andrew Schmelyun</a>@if(env('APP_VERSION')) <span class="text-gray-300">[v{{ env('APP_VERSION') }}]</span>@endif</p>
    </footer>
</body>