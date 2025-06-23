<?php

namespace App\Filters;

class CompanyFilter extends Filter
{
    /**
    * Register filter properties
    */
    protected $filters = ['name'];

    public function name($value) 
    {
        return $this->builder->where('name','LIKE',"%{$value}%");
    }

}