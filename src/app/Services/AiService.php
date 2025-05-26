<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;

class AiService
{
    public function chat(array $options)
    {
        if (config('ai.engine') === 'openai') {
            return OpenAI::chat()->create($options);
        }

        // Llama-compatible chat endpoint
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('ai.llama.api_key'),
        ])->post(rtrim(config('ai.llama.base_url'), '/').'/v1/chat/completions', $options);

        return json_decode($response->body(), true);
    }

    public function audioTranscribe(array $options)
    {
        if (config('ai.engine') === 'openai') {
            return OpenAI::audio()->transcribe($options);
        }

        // Llama-compatible audio transcription stub
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('ai.llama.api_key'),
        ])
        ->attach('file', fopen(storage_path('app/audio/'.$options['model'] ?? '').'', 'r'))
        ->post(rtrim(config('ai.llama.base_url'), '/').'/v1/audio/transcriptions', $options);

        return json_decode($response->body(), true);
    }
}
