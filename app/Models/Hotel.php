<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'user_id', 'name', 'address', 'star_rating',
        'description', 'images', 'type', 'active',
        'city', 'country', 'phone_number', 'email_address', 'website_url',
        'check_in_time', 'check_out_time', 'amenities', 'policies',
        'latitude', 'longitude', 'price_per_night', 'currency_code',
        'availability_status', 'slug', 'owner_name', 'contact_person_name',
        'contact_person_email', 'contact_person_phone', 'additional_information',
        'approved_at', 'view_count', 'booking_count', 'min_stay_duration',
        'max_stay_duration', 'cancellation_policy_days'
        // Note: 'charge' was in original fillable, but seems less common than price_per_night.
        // If 'charge' is a specific field still in use, it should be added back.
        // For now, using the comprehensive list from recent factory/test.
    ];

    protected $casts = [
        'images' => 'array',
        'amenities' => 'array', // Assuming amenities are stored as JSON and should be array
        'active' => 'boolean',
        'star_rating' => 'float',
        'latitude' => 'float', // Common cast for geo coordinates
        'longitude' => 'float', // Common cast for geo coordinates
        'price_per_night' => 'float', // Or 'decimal:2'
        'is_approved' => 'boolean', // If there's an approval workflow
        'approved_at' => 'datetime',
        'view_count' => 'integer',
        'booking_count' => 'integer',
        'min_stay_duration' => 'integer',
        'max_stay_duration' => 'integer',
        'cancellation_policy_days' => 'integer',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Assuming 'user_id' on hotels table is the manager/owner
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // For staff associated via user_hotels pivot table
    public function staffUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_hotels', 'hotel_id', 'user_id')
            ->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        // Ensure 'services_id' is correct for your pivot table, often it's 'service_id'
        return $this->belongsToMany(Service::class, 'hotel_services', 'hotel_id', 'service_id')
            ->withPivot(['id', 'charge', 'is_active', 'notes']) // Example pivot fields
            ->withTimestamps();
    }

    public function hotelServices(): HasMany
    {
        return $this->hasMany(HotelService::class, 'hotel_id');
    }

    // This might be redundant if staffUsers() serves the purpose.
    // UserHotel is the pivot model, direct relation to it from Hotel might not be needed
    // if the BelongsToMany through User is preferred.
    // public function userHotels()
    // {
    //     return $this->hasMany(UserHotel::class);
    // }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'hotel_id');
    }

    // Scope for approved hotels if 'is_approved' field is used
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->whereNotNull('approved_at');
    }
}
