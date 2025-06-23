<?php

namespace App\Filters;

class StationFilter extends Filter
{
    /**
    * Register filter properties
    */
    protected $filters = ['name'];

    public function name($value) 
    {
        return $this->builder->where('name','LIKE',"%{$value}%");
    }

    /**
    * Filter by date range.
    */
 

}