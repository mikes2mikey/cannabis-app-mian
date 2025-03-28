<?php

namespace App\Observers;

use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserObserver
{
    public function created(User $user): void
    {
        // Generate a unique identifier for the QR code
        $identifier = Str::uuid();
        
        // Generate QR code as SVG
        $qrCode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($identifier);

        // Save QR code to storage
        $path = 'qrcodes/' . $identifier . '.svg';
        Storage::disk('public')->put($path, $qrCode);

        // Update user with QR code information
        $user->update([
            'qr_code' => $path,
        ]);
    }

    public function deleted(User $user): void
    {
        // Delete QR code file when user is deleted
        if ($user->qr_code) {
            Storage::disk('public')->delete($user->qr_code);
        }
    }
} 