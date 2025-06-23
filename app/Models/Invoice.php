<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'user_id',
        'branch_id',
        'period_id',
        'invoice_number',
        'invoice_delivery_date',
        'invoice_due_date',
        'total_quantity',
        'total_taxes',
        'total_deposits',
        'total_delivery',
        'total_non_inventory',
        'total_misc',
        'total_cost_excluding_taxes',
        'total_cost'
    ];
    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }
    /**
    * Relationships.
    */
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id', 'vendor_id');
    }
    public function invoiceDetails()
    {
        return $this->hasmany(InvoiceDetails::class, 'invoice_id', 'id');
    }
}
