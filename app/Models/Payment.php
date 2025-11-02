<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tenant_id',
        'listing_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'payment_date',
        'notes',
        'transaction_details',
        'receipt_url',
        'is_on_time',
        'verified_at',
        'rejected_at',
        'verified_by',
        'rejected_by',
        'rejection_reason',
        'refunded_at',
        'refund_reason',
        'refunder',
        'disputed_at',
        'dispute_reason',
        'disputer',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'transaction_details' => 'array',
        'is_on_time' => 'boolean',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'refunded_at' => 'datetime',
        'disputed_at' => 'datetime',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    public function landlord()
    {
        return $this->hasOneThrough(User::class, Listing::class, 'id', 'id', 'listing_id', 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
