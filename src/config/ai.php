<?php

return [
    'engine' => env('AI_ENGINE', 'openai'),

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
    ],

    'llama' => [
        'base_url' => env('LLAMA_API_URL'),
        'api_key' => env('LLAMA_API_KEY'),
    ],
];
