<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id', 
        'item_size_id', 
        'qty', 
        'unit_from', 
        'unit_to', 
        'package_barcode'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
