<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStep extends Model
{
    protected $guarded = [];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
