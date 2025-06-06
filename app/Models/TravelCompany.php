<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelCompany extends Model
{
    
    protected $fillable = [
        'company_name',
        'email',
        'phone',
        'status',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
