<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrCodeController extends Controller
{
    /**
     * Verify a QR code and return the associated user information
     */
    public function verify(Request $request)
    {
        $request->validate([
            'qr_code_data' => 'required|string',
        ]);

        try {
            // Extract user ID from QR code data (assuming format: "user:123")
            $qrCodeData = $request->input('qr_code_data');
            
            // Check if this is a valid user-related QR code
            if (strpos($qrCodeData, 'user:') === 0) {
                $userId = substr($qrCodeData, 5); // Remove 'user:' prefix
                
                $user = User::with('profile')->find($userId);
                
                if (!$user) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User not found'
                    ], 404);
                }
                
                // Successfully found user
                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    ],
                    'user_profile' => $user->profile ? [
                        'phone' => $user->profile->phone,
                        'address' => $user->profile->address,
                    ] : null,
                ]);
            }
            
            // Not a user QR code or unrecognized format
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code format'
            ], 400);
            
        } catch (\Exception $e) {
            Log::error('QR code verification error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error processing QR code: ' . $e->getMessage()
            ], 500);
        }
    }
} 