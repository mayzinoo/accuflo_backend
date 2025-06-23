<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name','phone_no','address', 'company_id'];

    protected $table = 'branches';

    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }
}
