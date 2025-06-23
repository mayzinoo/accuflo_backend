<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id', 
        'station_id', 
        'section_id',
        'shelf_id', 
        'period_id',
        'user_id', 
        'branch_id', 
        'weight',
        "unit_id",
        "size",
        "package_id",
        'already_updated', 
        'mobile_submit_time',
    ];

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function section() {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function shelf() {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function package() {
        return $this->belongsTo(ItemPackage::class, 'package_id', 'id');
    }
    
    // public function itemSize()
    // {
    //     return $this->belongsTo(ItemSize::class, 'item_id');
    // }
}
