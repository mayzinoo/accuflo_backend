<?php

namespace App\Imports;

use App\Models\Classes;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemSize;
use App\Models\ItemPackage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ItemPackagesImport implements ToModel, WithStartRow
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
    {
        $class_name=$row[1];
        $category_name=$row[2];
        $class=Classes::where('name',$class_name)->get();
        $category=Category::where('name',$category_name)->where('class_id',$class[0]->id)->get();
        $item=Item::where('name',$row[3])->where('class_id',$class[0]->id)->where('category_id',$category[0]->id)->get();
        
        return new ItemPackage([
            //
        ]);
    }
}
