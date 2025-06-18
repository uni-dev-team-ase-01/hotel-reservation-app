<?php

namespace App\Models;

use App\Enum\RateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_number',
        'room_type',
        'occupancy',
        'location',
        'images',
    ];

    protected $casts = [
        'images' => 'array',
        'occupancy' => 'integer',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function reservationRooms(): HasMany
    {
        return $this->hasMany(ReservationRoom::class, 'room_id');
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_rooms', 'room_id', 'reservation_id')
            ->withTimestamps();
    }

    public function rates(): HasMany
    {
        return $this->hasMany(RoomRate::class, 'room_id');
    }

    public function getCurrentRate($rateType = RateType::DAILY->value)
    {
        return $this->rates()
            ->where('rate_type', $rateType)
            ->first()?->amount ?? 0;
    }

    public function getFullNameAttribute()
    {
        return $this->hotel->name.' - Room '.$this->room_number;
    }

    // need review
    // public function isAvailableForDates($checkIn, $checkOut)
    // {
    //     return ! $this->reservations()
    //         ->where(function ($query) use ($checkIn, $checkOut) {
    //             $query->where(function ($q) use ($checkIn) {
    //                 $q->where('check_in_date', '<=', $checkIn)
    //                     ->where('check_out_date', '>', $checkIn);
    //             })->orWhere(function ($q) use ($checkOut) {
    //                 $q->where('check_in_date', '<', $checkOut)
    //                     ->where('check_out_date', '>=', $checkOut);
    //             })->orWhere(function ($q) use ($checkIn, $checkOut) {
    //                 $q->where('check_in_date', '>=', $checkIn)
    //                     ->where('check_out_date', '<=', $checkOut);
    //             });
    //         })
    //         ->whereIn('status', ['confirmed', 'checked_in'])
    //         ->exists();
    // }

    public function scopeAvailableForDates($query, $checkIn, $checkOut, $hotelId = null)
    {
        // Fix the date overlap logic
        $query->whereDoesntHave('reservations', function ($reservationQuery) use ($checkIn, $checkOut) {
            $reservationQuery->whereNotIn('status', ['cancelled', 'no_show'])
                ->where(function ($dateQuery) use ($checkIn, $checkOut) {
                    // Proper overlap detection: reservation overlaps if:
                    // reservation check_in < our check_out AND reservation check_out > our check_in
                    $dateQuery->where('check_in_date', '<', $checkOut)
                        ->where('check_out_date', '>', $checkIn);
                });
        });

        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }

        return $query;
    }

    public function roomRates()
    {
        return $this->hasMany(RoomRate::class);
    }

    public function roomRevenueReports(): HasMany
    {
        return $this->hasMany(RoomRevenueReport::class, 'room_id');
    }
}
