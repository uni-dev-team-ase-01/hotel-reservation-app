<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
    ];

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class, 'hotel_services', 'services_id', 'hotel_id')
            ->withPivot(['id', 'charge'])
            ->withTimestamps();
    }

    public function bills(): BelongsToMany
    {
        return $this->belongsToMany(Bill::class, 'bill_services')
            ->withPivot('charge')
            ->withTimestamps();
    }

    public function billServices(): HasMany
    {
        // return $this->hasMany(BillService::class);
        return $this->hasMany(BillService::class, 'service_id');
    }

    public function hotelServices(): HasMany
    {
        return $this->hasMany(HotelService::class, 'services_id');
    }
}
