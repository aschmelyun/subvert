<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Media extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';

    protected $guarded = [];

    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'transcribed_at' => 'datetime',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function generatedContents(): HasMany
    {
        return $this->hasMany(GeneratedContent::class);
    }
}
