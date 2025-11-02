<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'lease_start_date',
        'title',
        'description',
        'price',
        'room_count',
        'bathroom_count',
        'property_type',
        'location',
        'status',
        'featured_image',
        'images',
        'amenities',
        'available_from',
        'available_to',
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array',
        'available_from' => 'date',
        'available_to' => 'date',
        'lease_start_date' => 'date',
    ];

    /**
     * Get the images attribute - handle double-encoded JSON
     */
    public function getImagesAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }

        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }

        // If it's a string, try to decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            // If decoding fails or returns null, return empty array
            if ($decoded === null) {
                return [];
            }
            // If it's already an array after decoding, return it
            if (is_array($decoded)) {
                return $decoded;
            }
            // If it's still a string (double-encoded), decode again
            if (is_string($decoded)) {
                $doubleDecoded = json_decode($decoded, true);
                return is_array($doubleDecoded) ? $doubleDecoded : [];
            }
        }

        return [];
    }

    /**
     * Set the images attribute
     */
    public function setImagesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['images'] = json_encode($value);
        } elseif (is_string($value)) {
            $this->attributes['images'] = $value;
        } else {
            $this->attributes['images'] = json_encode([]);
        }
    }

    /**
     * Get the user that owns the listing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the tenant who rented the listing.
     */
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    /**
     * Get the users who favorited this listing.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Get the users who liked this listing.
     */
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    /**
     * Get the rental applications for this listing.
     */
    public function rentalApplications()
    {
        return $this->hasMany(RentalApplication::class, 'listing_id');
    }

    /**
     * Get the count of likes for this listing.
     */
    public function getLikesCountAttribute()
    {
        return $this->likedBy()->count();
    }

    /**
     * Scope for active listings
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for rented listings
     */
    public function scopeRented($query)
    {
        return $query->where('status', 'rented');
    }

    /**
     * Check if listing is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if listing is rented
     */
    public function isRented()
    {
        return $this->status === 'rented';
    }
}
