<?php

namespace App\Services;

use App\Models\User;
use App\Models\VerificationHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerificationApproved;
use App\Notifications\VerificationRejected;

class VerificationService
{
    /**
     * Approve user verification
     */
    public function approveVerification(User $user, User $admin, string $notes = null): bool
    {
        try {
            // Update user verification status
            $user->update([
                'verification_status' => 'approved',
                'verified_at' => now(),
                'verification_notes' => $notes,
            ]);

            // Log the approval in verification history
            VerificationHistory::create([
                'user_id' => $user->id,
                'action' => 'approved',
                'admin_id' => $admin->id,
                'notes' => $notes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Send notification to user
            try {
                $user->notify(new VerificationApproved($user));
            } catch (\Exception $e) {
                Log::warning('Failed to send verification approval notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('User verification approved', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'verification_id' => $user->verification_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to approve verification', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Reject user verification
     */
    public function rejectVerification(User $user, User $admin, string $notes): bool
    {
        try {
            // Update user verification status
            $user->update([
                'verification_status' => 'declined',
                'verified_at' => null,
                'verification_notes' => $notes,
            ]);

            // Log the rejection in verification history
            VerificationHistory::create([
                'user_id' => $user->id,
                'action' => 'rejected',
                'admin_id' => $admin->id,
                'notes' => $notes,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Send notification to user
            try {
                $user->notify(new VerificationRejected($user, $notes));
            } catch (\Exception $e) {
                Log::warning('Failed to send verification rejection notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }

            Log::info('User verification rejected', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'verification_id' => $user->verification_id,
                'reason' => $notes,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to reject verification', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Ban user account
     */
    public function banUser(User $user, User $admin, string $reason): bool
    {
        try {
            // Store original verification status
            $originalStatus = $user->verification_status ?? 'pending';

            // Update user status
            $user->update([
                'verification_status' => 'banned',
                'verification_notes' => 'Account banned by administrator: ' . $reason . ' | Original status: ' . $originalStatus . ' | ' . ($user->verification_notes ?? ''),
            ]);

            // Log the ban in verification history
            VerificationHistory::create([
                'user_id' => $user->id,
                'action' => 'banned',
                'admin_id' => $admin->id,
                'notes' => $reason,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            Log::info('User account banned', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'reason' => $reason,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to ban user', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Unban user account
     */
    public function unbanUser(User $user, User $admin): bool
    {
        try {
            // Restore original verification status from notes
            $originalStatus = 'pending';
            $originalNotes = '';

            if ($user->verification_notes && str_starts_with($user->verification_notes, 'Account banned by administrator:')) {
                $parts = explode(' | ', $user->verification_notes, 3);
                if (count($parts) >= 2) {
                    // Extract original status from the second part
                    $statusPart = $parts[1];
                    if (str_starts_with($statusPart, 'Original status: ')) {
                        $originalStatus = str_replace('Original status: ', '', $statusPart);
                    }
                    // Extract original notes from the third part if exists
                    if (isset($parts[2])) {
                        $originalNotes = $parts[2];
                    }
                }
            }

            // Update user status
            $user->update([
                'verification_status' => $originalStatus,
                'verification_notes' => $originalNotes ?: null,
            ]);

            // Log the unban in verification history
            VerificationHistory::create([
                'user_id' => $user->id,
                'action' => 'unbanned',
                'admin_id' => $admin->id,
                'notes' => 'Account unbanned by administrator',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            Log::info('User account unbanned', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'restored_status' => $originalStatus,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to unban user', [
                'user_id' => $user->id,
                'admin_id' => $admin->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get verification statistics
     */
    public function getVerificationStats(): array
    {
        return [
            'total_users' => User::count(),
            'pending_verifications' => User::where('verification_status', 'pending')->count(),
            'approved_verifications' => User::where('verification_status', 'approved')->count(),
            'rejected_verifications' => User::where('verification_status', 'declined')->count(),
            'banned_users' => User::where('verification_status', 'banned')->count(),
            'unverified_users' => User::whereNull('verification_status')->count(),
        ];
    }

    /**
     * Get recent verification activities
     */
    public function getRecentActivities(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return VerificationHistory::with(['user', 'admin'])
            ->latest()
            ->limit($limit)
            ->get();
    }
}
