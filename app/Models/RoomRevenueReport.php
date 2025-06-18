<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomRevenueReport extends Model
{
    protected $fillable = [
        'hotel_id',
        'room_id',
        'date',
        'revenue',
        'occupancy',
    ];

    protected $casts = [
        'date' => 'date',
        'revenue' => 'decimal:2',
        'occupancy' => 'integer',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
