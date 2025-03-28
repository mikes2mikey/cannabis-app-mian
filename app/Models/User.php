<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'qr_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the plants associated with the user
     *
     * @return BelongsToMany
     */
    public function plants(): BelongsToMany
    {
        return $this->belongsToMany(Plant::class)->withTimestamps();
    }

    /**
     * Get the membership plan associated with the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class);
    }
    
    /**
     * Get the user profile
     *
     * @return HasOne
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Generate and store a QR code for the user
     *
     * @return string The generated QR code in SVG format
     */
    public function generateQrCode(): string
    {
        // Generate QR code with user ID information to make it scannable and identifiable
        $qrCodeData = 'user:' . $this->id;
        $qrCode = QrCode::size(300)
            ->format('svg')
            ->generate($qrCodeData);
        
        $this->update(['qr_code' => $qrCodeData]);
        
        return $qrCode;
    }

    /**
     * Get the user's QR code
     *
     * @return string|null The QR code in SVG format
     */
    public function getQrCode(): ?string
    {
        if (!$this->qr_code) {
            // If no QR code exists, create one with user ID
            $qrCodeData = 'user:' . $this->id;
            $this->update(['qr_code' => $qrCodeData]);
            return QrCode::size(300)
                ->format('svg')
                ->generate($qrCodeData);
        }

        return QrCode::size(300)
            ->format('svg')
            ->generate($this->qr_code);
    }
}
