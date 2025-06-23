<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'invoice_due_date',
        'invoice_due_date_unit',
        'status',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country_code',
        'postal_code',
        'phone',
        'cell',
        'fax',
        'email',
        'notes'
    ];

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }
}
