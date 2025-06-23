<?php

namespace App\Filters;

class UserFilter extends Filter
{
    /**
    * Register filter properties
    */
    protected $filters = ['email', 'date'];

    public function email($value) 
    {
        return $this->builder->where('email', 'LIKE', "%{$value}%")->orWhere('name', 'LIKE', "%{$value}%");
    }

    /**
    * Filter by date range.
    */
    public function date($value)
    {
        $date = split_daterange($value);
        return $this->builder->where('users.created_at', '>=', $date['from'])
                            ->where('users.created_at', '<=', $date['to']);
    }

}