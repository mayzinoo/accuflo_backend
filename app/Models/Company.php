<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone_no','address'];

    protected $table = 'companies';

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }
}
