<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'branch_id','station_id','item_id','item_size_id','item_package_id',
    'current_period_count','current_period_weight','unit','submit_time','shelf','section','device'];

}
