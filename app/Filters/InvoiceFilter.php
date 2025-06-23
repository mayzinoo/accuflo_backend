<?php

namespace App\Filters;

class InvoiceFilter extends Filter
{
    /**
    * Register filter properties
    */
    protected $filters = ['invoice_number'];

    public function invoice_number($value) 
    {
        return $this->builder->where('invoice_number','LIKE',"%{$value}%");
    }

    /**
    * Filter by date range.
    */
 

}