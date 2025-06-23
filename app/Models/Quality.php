<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quality extends Model
{
    use HasFactory;

    protected $fillable = ['name','class_id'];

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
