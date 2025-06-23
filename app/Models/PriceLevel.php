<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceLevel extends Model
{
    use HasFactory;
    protected $fillable = [
        'station_id', 
        'level',
        'type',
        'client_id',
        'period_id'
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
