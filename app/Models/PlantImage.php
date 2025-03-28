<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'image_path',
        'thumbnail_path',
        'description'
    ];

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
} 