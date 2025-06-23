<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Classes;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ItemsImport implements ToModel, WithStartRow
{
    public function __construct($user_id,$period_id){
        $this->user_id=$user_id;
        $this->period_id=$period_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function startRow():int {
        return 5;
    }
    public function model(array $row)
    { 
        $class_name=$row[1];
        $category_name=$row[2];
        $class=Classes::where('name',$class_name)->get();
        $category=Category::where('name',$category_name)->where('class_id',$class[0]->id)->get();
        //need to add new class and category if no data in db?
        
        return new Item([
            'name' => $row[3],
            'class_id'=> $class[0]->id,
            'category_id' => $category[0]->id,
            'quality_id' => 0,
            'user_id' => $this->user_id,
            'period_id' => $this->period_id
        ]);
    }
}
