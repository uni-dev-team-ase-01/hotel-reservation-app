<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'star_rating',
        'images',
        'website',
        'active',
        'charge',
        'address',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_hotels', 'hotel_id', 'user_id')
            ->withTimestamps();
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'hotel_services', 'hotel_id', 'services_id')
            ->withPivot(['id', 'charge'])
            ->withTimestamps();
    }

    public function hotelServices(): HasMany
    {
        return $this->hasMany(HotelService::class, 'hotel_id');
    }

    public function userHotels()
    {
        return $this->hasMany(UserHotel::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'hotel_id');
    }
}
