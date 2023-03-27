<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'status' => Status::class,
        'options' => 'array',
    ];

    public function getTranscriptArrayAttribute()
    {
        $transcript = explode("\n", $this->transcript);

        array_shift($transcript);
        $transcript = array_filter($transcript, fn($value) => $value !== '');

        $transcript = array_values($transcript);

        return $transcript;
    }

    public function getTranscriptChunksAttribute()
    {
        $transcript = $this->transcriptArray;

        return array_chunk($transcript, env('TRANSCRIPT_CHUNK_SIZE', 100));
    }
}
