<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrowthLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'date',
        'notes',
        'height',
        'phase',
        'temperature',
        'humidity'
    ];

    protected $casts = [
        'date' => 'date',
        'height' => 'decimal:2',
        'temperature' => 'decimal:1',
        'humidity' => 'decimal:1'
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
} 