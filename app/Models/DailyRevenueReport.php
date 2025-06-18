<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRevenueReport extends Model
{
    protected $fillable = [
        'hotel_id',
        'date',
        'type',
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
}
