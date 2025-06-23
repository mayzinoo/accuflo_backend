<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'recipe_id',
        'price_level_id',
        'price',
        'qty',
        'revenue'
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    public function pricelevel()
    {
        return $this->hasMany(PriceLevel::class, 'id', 'price_level_id');
    }
}
