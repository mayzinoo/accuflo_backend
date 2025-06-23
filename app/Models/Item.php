<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'class_id', 
        'category_id', 
        'quality_id', 
        'user_id',
        'branch_id',
        'period_id',
    ];

    
    public function scopeFilter($query, $filter)
    {
        $filter->apply($query);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    } 

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function quality()
    {
        return $this->belongsTo(Quality::class);
    }

    public function itemSize()
    {
        return $this->hasMany(ItemSize::class);
    }

    public function itemPackage()
    {
        return $this->hasMany(ItemPackage::class);
    }

    public const PACKAGENAME = [
		'BAG' => 'BAG',
		'BLOCK' => 'BLOCK',
		'BOX' => 'BOX',
        'CARTON' => 'CARTON',
        'CASE' => 'CASE',
        'CRATE' => 'CRATE',
        'LOAF' => 'LOAF',
        'PACKAGE' => 'PACKAGE',
        'TRAY' => 'TRAY'
	];

     /**
    * Relationships.
    */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetails::class, 'item_id', 'id');
    }
}
