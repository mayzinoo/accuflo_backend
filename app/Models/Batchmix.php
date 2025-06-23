<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batchmix extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'barcode',
        'code',
        'unit_des',
        'inventory_status',
        'total_weight',
        'total_weight_id',
        'container_weight',
        'container_weight_id',
        'liquid_status',
        'total_volume',
        'total_volume_id',
        'density',
        'user_id',
        'branch_id',
        'period_id'
    ];

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }

    public function ingredients()
    {
        return $this->hasMany(BatchmixIngredients::class);
    }
}
