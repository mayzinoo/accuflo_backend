<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FullCount extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'size','period_id', 'user_id', 'branch_id','station_id', 'period_count', 'mobile_sumbit_time', 'already_updated', 'package_id'];

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

    public function period()
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function itemPackage()
    {
        return $this->belongsTo(ItemPackage::class, 'package_id', 'id');
    }

    public function itemSize()
    {
        return $this->belongsTo(ItemSize::class, 'item_id', 'id');
    }

    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetails::class, 'item_id', 'item_id');
    }
}
