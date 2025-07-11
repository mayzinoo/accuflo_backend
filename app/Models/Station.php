<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id', 'branch_id', 'period_id'];

    /**
    * Relationships.
    */
    public function sections()
    {
        return $this->hasMany(Section::class, 'station_id', 'id');
    }

}
