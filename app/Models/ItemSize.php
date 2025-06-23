<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSize extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'countable_unit', 
        'countable_size', 
        'empty_weight', 
        'empty_weight_size', 
        'full_weight', 
        'full_weight_size', 
        'density', 
        'density_m_unit', 
        'density_v_unit', 
        'sizeoption', 
        'quantification',
        // 'barcode',
        'package_status'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }

    public function itemPackage()
    {
        return $this->hasMany(ItemPackage::class);
    }
}
