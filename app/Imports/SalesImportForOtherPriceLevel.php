<?php

namespace App\Imports;

use App\Models\RecipeSale;
use App\Models\Recipe;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class SalesImportForOtherPriceLevel implements ToModel,WithStartRow,SkipsEmptyRows
{
    public function __construct($station_id,$price_level_id){
        $this->station_id=$station_id;
        $this->price_level_id=$price_level_id;
        
    }
    public function startRow():int {
        return 12;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $name='';
        if($row[1]){
            $name=$row[1];
        }
        //skip data if total row or printed row
        if(str_contains($row[0],'Total') || str_contains($row[0],'Bill') || str_contains($row[0],'Printed')){
            return [];
        }
        $recipe_id=0;
        $recipe=Recipe::where('plu',$row[0])->where('name',$name)->where('station_id',$this->station_id)
        ->latest('created_at')->first();
        
        return new RecipeSale([
            'recipe_id'       => $recipe->id,
            'price_level_id'  => $this->price_level_id,
            'qty'             => 0,
            'price'           => 0,
            'revenue'         => 0,
        ]);
    }
}
