<?php

namespace App\Filters;

class FullCountFilter extends Filter
{
    /**
    * Register filter properties
    */
    protected $filters = ['item_name', 'station_id'];

    public function item_name($value) 
    {
        return $this->builder->whereHas('item', function($query) use($value){
            $query->where('name','LIKE',"%{$value}%");
        });
    }

    public function station_id($value) 
    {
        return $this->builder->where('station_id', $value);
    }

}