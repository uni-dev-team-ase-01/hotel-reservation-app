<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OccupancyPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'date',
        'predicted_occupancy',
        'actual_occupancy',
        'confidence_score',
        'prediction_method',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'predicted_occupancy' => 'decimal:2',
        'actual_occupancy' => 'decimal:2',
        'confidence_score' => 'decimal:2'
    ];

    // Calculate prediction accuracy
    public function getAccuracyAttribute()
    {
        if ($this->actual_occupancy === null) {
            return null;
        }
        
        $diff = abs($this->predicted_occupancy - $this->actual_occupancy);
        return max(0, 100 - ($diff / $this->actual_occupancy * 100));
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}