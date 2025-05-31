<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    private const DECIMAL_CAST = 'decimal:2';

    protected $fillable = [
        'reservation_id',
        'room_charges',
        'extra_charges',
        'discount',
        'taxes',
        'total_amount',
        'payment_status',
    ];

    protected $casts = [
        'room_charges' => self::DECIMAL_CAST,
        'extra_charges' => self::DECIMAL_CAST,
        'discount' => self::DECIMAL_CAST,
        'taxes' => self::DECIMAL_CAST,
        'total_amount' => self::DECIMAL_CAST,
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function billServices(): HasMany
    {
        return $this->hasMany(BillService::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'bill_services')
            ->withPivot('charge')
            ->withTimestamps();
    }
}
