<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'previous_status',
        'new_status',
        'verification_id',
        'document_type',
        'notes',
        'admin_id',
        'metadata',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that this verification history belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who performed this action.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope to get recent activities
     */
    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope to filter by action type
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Get formatted action display name
     */
    public function getActionDisplayAttribute(): string
    {
        return match($this->action) {
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'banned' => 'Banned',
            'unbanned' => 'Unbanned',
            default => ucfirst($this->action)
        };
    }

    /**
     * Get formatted created date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }
}
