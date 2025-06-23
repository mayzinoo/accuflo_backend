<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'plu',
        'user_id',
        'branch_id',
        'period_id',
        'station_id',
        'tax'
    ];

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }

    public function ingredients()
    {
        return $this->hasMany(RecipeIngredients::class);
    }

     public function sales()
    {
        return $this->hasMany(RecipeSale::class);
    }

    /* public function saleprice()
    {
        return $this->hasMany(RecipeSalePriceLevel::class);
    } */
}
