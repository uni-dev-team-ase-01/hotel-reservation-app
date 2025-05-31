<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelService extends Model
{
    protected $fillable = [
        'hotel_id',
        'services_id',
        'charge',
    ];

    protected $casts = [
        'charge' => 'decimal:2',
    ];

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'services_id');
    }
}
