<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = ['name','type'];

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'class_id', 'id');
    }

    public function qualities()
    {
        return $this->hasMany(Quality::class, 'class_id', 'id');
    }
}
