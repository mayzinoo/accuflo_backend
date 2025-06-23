<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'purchased_quantity',
        'purchase_package',
        'unit_price',
        'extended_price'
    ];
    /**
    * Relationships.
    */
    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }
    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id');
    }

    public function itemPackage()
    {
        return $this->hasMany(ItemPackage::class, 'id', 'purchase_package');
    }

}
