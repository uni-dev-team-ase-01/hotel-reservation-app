<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'hotel_id',
        'check_in_date',
        'check_out_date',
        'status',
        'number_of_guests',
        'cancellation_reason',
        'cancellation_date',
        'confirmation_number',
        'auto_cancelled',
        'no_show_billed',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'cancellation_date' => 'date',
        'auto_cancelled' => 'boolean',
        'no_show_billed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'No User Assigned';
    }

    public function reservationRooms(): HasMany
    {
        return $this->hasMany(ReservationRoom::class, 'reservation_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'reservation_rooms', 'reservation_id', 'room_id')
            ->withTimestamps();
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class, 'reservation_id');
    }

    public function getTotalBillAmountAttribute()
    {
        return $this->bills()->sum('total_amount');
    }

    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function getHotelNameAttribute()
    {
        return $this->hotel ? $this->hotel->name : 'No Hotel Assigned';
    }
}
