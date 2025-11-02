<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    protected $fillable = [
        'tenant_id',
        'listing_id',
        'full_name',
        'phone',
        'email',
        'planned_move_in_date',
        'planned_end_date',
        'employment_status',
        'monthly_income',
        'occupants',
        'reason_for_moving',
        'additional_notes',
        'document_url',
        'status',
    ];

    protected $casts = [
        'planned_move_in_date' => 'date',
        'planned_end_date' => 'date',
        'monthly_income' => 'decimal:2',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }
}
