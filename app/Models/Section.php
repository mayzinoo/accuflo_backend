<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'station_id', 'section_id'];

    /**
    * Relationships.
    */
    public function shelves()
    {
        return $this->hasMany(Shelf::class, 'section_id', 'id');
    }
}
