<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeIngredients extends Model
{
    use HasFactory;
    protected $fillable = [
        'recipe_id',
        'item_id',
        'qty',
        'package_id ',
        'uom_text'
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function invoiceDetail()
    {
        return $this->belongsTo(InvoiceDetails::class, 'item_id', 'item_id');
    }

    public function itemPackage()
    {
        return $this->hasMany(ItemPackage::class, 'id', 'package_id');
    }

    // public function period()
    // {
    //     return $this->belongsTo(Period::class, 'period_id', 'id');
    // }
}
