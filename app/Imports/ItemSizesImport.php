<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\ItemSize;
use App\Models\Classes;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ItemSizesImport implements ToModel, WithStartRow
{
    public function startRow():int{
        return 5;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   $countable_unit=0;
        $countable_size='';
        $class_name=$row[1];
        $category_name=$row[2];
        $class=Classes::where('name',$class_name)->get();
        $category=Category::where('name',$category_name)->where('class_id',$class[0]->id)->get();
        $item=Item::where('name',$row[3])->where('class_id',$class[0]->id)->where('category_id',$category[0]->id)->get();
        if(!str_contains($row[4],'-')){
            preg_match_all('/[0-9]+/', $row[4], $countable_unit_array);
            //countable unit can be two array for 24 x 1 BOTTLE //need to fix later
            $countable_unit=$countable_unit_array[0][0];
            $pattern="/{$countable_unit}/i";
            $countable_size=trim(preg_replace($pattern,'',$row[4]));
        }
    
        return new ItemSize([
            'item_id' => $item[0]->id,
            'countable_unit' => $countable_unit,
            'countable_size' => $countable_size,
            'empty_weight_size' => '' ,
            'full_weight_size' => '',
            'package_status' => ''
        ]);
    }
}
