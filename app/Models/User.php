<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'first_name',
        'surname',
        'name',
        'email',
        'role',
        'phone',
        'password',
        'profile_photo_path',
        'valid_id_path',
        'valid_id_back_path',
        'verification_status',
        'verification_notes',
        'verification_id',
        'document_type',
        'verified_at',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'verified_at' => 'datetime',
        'last_activity' => 'datetime',
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
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Get the listings for the user.
     */
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Update last activity timestamp to now.
     */
    public function updateLastActivity()
    {
        $this->last_activity = now();
        $this->save();
    }

    /**
     * Check if user is currently active (last activity within 1 hour).
     * If no last_activity exists (new users), consider them active.
     */
    public function isActive()
    {
        return !$this->last_activity || $this->last_activity->gt(now()->subHour());
    }

    /**
     * Get the favorite listings for the user.
     */
    public function favoriteListings()
    {
        return $this->belongsToMany(Listing::class, 'favorites')->withTimestamps();
    }

    /**
     * Get the liked listings for the user.
     */
    public function likedListings()
    {
        return $this->belongsToMany(Listing::class, 'likes')->withTimestamps();
    }

    /**
     * Get the verification histories for the user.
     */
    public function verificationHistories()
    {
        return $this->hasMany(VerificationHistory::class);
    }

    /**
     * Get the rental applications for the user (as tenant).
     */
    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class, 'tenant_id');
    }

    /**
     * Get the role relationship.
     */
    public function roleRelation()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }

    /**
     * Get the role name attribute.
     */
    public function getRoleAttribute($value)
    {
        // Return the direct database value if it exists
        if ($value) {
            return $value;
        }

        // Fallback for roleRelation if it exists
        if ($this->roleRelation) {
            return $this->roleRelation->role_name;
        }

        // Fallback mapping for role_id
        return match($this->role_id ?? null) {
            1 => 'admin',
            2 => 'landlord',
            3 => 'tenant',
            default => 'unknown'
        };
    }

    /**
     * Check if user is verified landlord
     */
    public function isVerifiedLandlord()
    {
        return $this->role === 'landlord' && $this->verification_status === 'approved';
    }

    /**
     * Determine if the user can post listings.
     */
    public function canPostListings()
    {
        return $this->role === 'landlord' && $this->verification_status === 'approved';
    }

    /**
     * Accessor for document_type attribute.
     * Returns 'not_specified' if the value is empty or null.
     */
    public function getDocumentTypeAttribute($value)
    {
        return $value ?: 'not_specified';
    }
}
