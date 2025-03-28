<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'is_active',
        'is_recurring'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'is_active' => 'boolean',
        'is_recurring' => 'boolean',
    ];

    /**
     * Get the users that belong to this membership plan
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Calculate the duration in months for recurring billing
     * 
     * @return int Number of months for billing cycles
     */
    public function calculateDurationMonths(): int
    {
        // Convert days to approximate months (using 30 days per month)
        return max(1, ceil($this->duration_days / 30));
    }
}
