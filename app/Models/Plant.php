<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'strain',
        'planting_date',
        'harvest_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'planting_date' => 'date',
        'harvest_date' => 'date',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function growthLogs(): HasMany
    {
        return $this->hasMany(GrowthLog::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PlantImage::class);
    }
} 