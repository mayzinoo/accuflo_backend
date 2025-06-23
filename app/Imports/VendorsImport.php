<?php

namespace App\Imports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class VendorsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {   
        
        return new Vendor([
            'name' => $row['name'],
            'code' => $row['code'] ? $row['code'] : '',
            'address_line_1' => $row['address1'] ,
            'address_line_2' => $row['address2'] ? $row['address2'] : '',
            'city' => $row['city'],
            'state' => $row['state'],
            'country_code' => $row['country_code'] ? $row['country_code'] : '',
            'postal_code' => $row['postal_code'],
            'phone' => $row['phone'] ? $row['phone'] : '',
            'cell'=> $row['cell'] ? $row['cell'] : '',
            'fax' => $row['fax']  ? $row['fax'] : '',
            'email' => $row['email'] ? $row['email'] : ''
          
        ]);
    }
}
