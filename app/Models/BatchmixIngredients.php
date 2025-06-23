<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchmixIngredients extends Model
{
    use HasFactory;
    protected $fillable = [
        'batchmix_id',
        'item_id',
        'period_id',
        'item_name',
        'qty',
        'uom'
    ];

    public function batchmix()
    {
        return $this->belongsTo(Batchmix::class, 'batchmix_id', 'id');
    }

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
