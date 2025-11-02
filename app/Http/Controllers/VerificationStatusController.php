<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VerificationStatusController extends Controller
{
    /**
     * Get verification status details for a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, int $userId): JsonResponse
    {
        $user = User::find($userId);

        if (!$user || !in_array($user->role, ['landlord', 'tenant'])) {
            return response()->json([
                'success' => false,
                'message' => 'User not found or invalid user type.'
            ], 404);
        }

        $validIdFrontUrl = null;
        $validIdBackUrl = null;

        if ($user->valid_id_path) {
            $validIdFrontUrl = url('image-server.php?path=' . urlencode($user->valid_id_path));
        }

        if ($user->valid_id_back_path) {
            $validIdBackUrl = url('image-server.php?path=' . urlencode($user->valid_id_back_path));
        }

        $userName = $user->name;
        if (!$userName && ($user->first_name || $user->surname)) {
            $userName = trim(($user->first_name ?? '') . ' ' . ($user->surname ?? ''));
        }
        if (!$userName) {
            $userName = 'N/A';
        }

        $verificationData = [
            'user_id' => $user->getKey(),
            'name' => $userName,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'verification_status' => $user->verification_status ?? 'pending',
            'document_type' => !empty($user->document_type) ? ucfirst(str_replace('_', ' ', $user->document_type)) : 'Not specified',
            'verification_notes' => $user->verification_notes,
            'created_at' => $user->created_at->format('M d, Y'),
            'front_image_url' => $validIdFrontUrl,
            'back_image_url' => $validIdBackUrl,
        ];

        return response()->json([
            'success' => true,
            'data' => $verificationData
        ]);
    }
}
