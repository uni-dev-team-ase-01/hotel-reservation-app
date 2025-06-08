<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'stripe_customer_id',
        'has_stripe_payment_method',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isCustomer()
    {
        return $this->hasRole('customer');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['customer', 'super-admin', 'travel-company', 'hotel-manager', 'hotel-clerk']);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class, 'user_hotels', 'user_id', 'hotel_id')
            ->withTimestamps();
    }

    public function userHotels(): HasMany
    {
        return $this->hasMany(UserHotel::class, 'user_id');
    }

    public function userHotelsRooms(): HasManyThrough
    {
        //     $hotelIds = $this->hotels()->pluck('hotels.id');

        //     return Room::whereIn('hotel_id', $hotelIds);

        return $this->hasManyThrough(
            Room::class,
            UserHotel::class,
            'user_id',
            'hotel_id',
            'id',
            'hotel_id'
        );
    }
}
