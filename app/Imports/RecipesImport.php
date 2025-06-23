<?php

namespace App\Imports;

use App\Models\Recipe;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class RecipesImport implements ToModel, WithStartRow, SkipsEmptyRows
{
    public function __construct($station_id,$branch_id,$period_id){
        $this->station_id=$station_id;
        $this->branch_id=$branch_id;
        $this->period_id=$period_id;

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
    {   //skip data if total row or printed row
        $converted_plu=strtolower($row[0]);
        if(str_contains($converted_plu,'total') || str_contains($converted_plu,'bill') || str_contains($converted_plu,'printed')){
            return [];
        }

        //need to check data if same data existed in db
        return new Recipe([
            'name'       => $row[1],
            'plu'        => $row[0],
            'station_id' => $this->station_id,
            'branch_id'    => $this->branch_id,
            'period_id'  => $this->period_id,
            'tax'        => 0,
        ]);
    }
}
