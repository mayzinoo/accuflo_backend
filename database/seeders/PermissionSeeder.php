<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'create full count',
                'title' => 'Full Count'
            ],
            [
                'name' => 'edit full count',
                'title' => 'Full Count'
            ],
            [
                'name' => 'list full count',
                'title' => 'Full Count'
            ],
            [
                'name' => 'delete full count',
                'title' => 'Full Count'
            ],
            [
                'name' => 'create weight',
                'title' => 'Weight'
            ],
            [
                'name' => 'edit weight',
                'title' => 'Weight'
            ],
            [
                'name' => 'list weight',
                'title' => 'Weight'
            ],
            [
                'name' => 'delete weight',
                'title' => 'Weight'
            ],
            [
                'name' => 'inventory upload via file',
                'title' => 'Inventory'
            ],
            [
                'name' => 'create invoice',
                'title' => 'Invoice'
            ],
            [
                'name' => 'edit invoice',
                'title' => 'Invoice'
            ],
            [
                'name' => 'list invoice',
                'title' => 'Invoice'
            ],
            [
                'name' => 'delete invoice',
                'title' => 'Invoice'
            ],
            [
                'name' => 'create vendor',
                'title' => 'Vendor'
            ],
            [
                'name' => 'edit vendor',
                'title' => 'Vendor'
            ],
            [
                'name' => 'list vendor',
                'title' => 'Vendor'
            ],
            [
                'name' => 'delete vendor',
                'title' => 'Vendor'
            ],
            [
                'name' => 'create sales',
                'title' => 'Sales And Recipe'
            ],
            [
                'name' => 'edit sales',
                'title' => 'Sales And Recipe'
            ],
            [
                'name' => 'list sales',
                'title' => 'Sales And Recipe'
            ],
            [
                'name' => 'delete sales',
                'title' => 'Sales And Recipe'
            ],
            [
                'name' => 'sales upload via file',
                'title' => 'Sales And Recipe'
            ],
            [
                'name' => 'management',
                'title' => 'Report'
            ],
            [
                'name' => 'variance',
                'title' => 'Report'
            ],
            [
                'name' => 'batch mix',
                'title' => 'Report'
            ],
            [
                'name' => 'sales',
                'title' => 'Report'
            ],
            [
                'name' => 'recipes',
                'title' => 'Report'
            ],
            [
                'name' => 'inventory',
                'title' => 'Report'
            ],
            [
                'name' => 'invoice summary',
                'title' => 'Report'
            ],
            [
                'name' => 'purchase summary',
                'title' => 'Report'
            ],
            [
                'name' => 'inventory breakdown',
                'title' => 'Report'
            ],
            [
                'name' => 'finalize period',
                'title' => 'Finalize Period'
            ],
            [
                'name' => 'create item',
                'title' => 'Item'
            ],
            [
                'name' => 'edit item',
                'title' => 'Item'
            ],
            [
                'name' => 'list item',
                'title' => 'Item'
            ],
            [
                'name' => 'delete item',
                'title' => 'Item'
            ],
            [
                'name' => 'create item size',
                'title' => 'Item Size'
            ],
            [
                'name' => 'edit item size',
                'title' => 'Item Size'
            ],
            [
                'name' => 'list item size',
                'title' => 'Item Size'
            ],
            [
                'name' => 'delete item size',
                'title' => 'Item Size'
            ],
            [
                'name' => 'create batch mix',
                'title' => 'BatchMix'
            ],
            [
                'name' => 'edit batch mix',
                'title' => 'BatchMix'
            ],
            [
                'name' => 'list batch mix',
                'title' => 'BatchMix'
            ],
            [
                'name' => 'delete batch mix',
                'title' => 'BatchMix'
            ],
            [
                'name' => 'create station',
                'title' => 'Station Setup'
            ],
            [
                'name' => 'edit station',
                'title' => 'Station Setup'
            ],
            [
                'name' => 'list station',
                'title' => 'Station Setup'
            ],
            [
                'name' => 'delete station',
                'title' => 'Station Setup'
            ],
            [
                'name' => 'create section',
                'title' => 'Section Setup'
            ],
            [
                'name' => 'edit section',
                'title' => 'Section Setup'
            ],
            [
                'name' => 'list section',
                'title' => 'Section Setup'
            ],
            [
                'name' => 'delete section',
                'title' => 'Section Setup'
            ],
            [
                'name' => 'create class',
                'title' => 'Class'
            ],
            [
                'name' => 'edit class',
                'title' => 'Class'
            ],
            [
                'name' => 'list class',
                'title' => 'Class'
            ],
            [
                'name' => 'delete class',
                'title' => 'Class'
            ],
            [
                'name' => 'create category',
                'title' => 'Category'
            ],
            [
                'name' => 'edit category',
                'title' => 'Category'
            ],
            [
                'name' => 'list category',
                'title' => 'Category'
            ],
            [
                'name' => 'delete category',
                'title' => 'Category'
            ],
            [
                'name' => 'create quality',
                'title' => 'Quality'
            ],
            [
                'name' => 'edit quality',
                'title' => 'Quality'
            ],
            [
                'name' => 'list quality',
                'title' => 'Quality'
            ],
            [
                'name' => 'delete quality',
                'title' => 'Quality'
            ],
            [
                'name' => 'create user',
                'title' => 'User'
            ],
            [
                'name' => 'edit user',
                'title' => 'User'
            ],
            [
                'name' => 'list user',
                'title' => 'User'
            ],
            [
                'name' => 'delete user',
                'title' => 'User'
            ],
            [
                'name' => 'create company',
                'title' => 'Company'
            ],
            [
                'name' => 'edit company',
                'title' => 'Company'
            ],
            [
                'name' => 'list company',
                'title' => 'Company'
            ],
            [
                'name' => 'delete company',
                'title' => 'Company'
            ],
            [
                'name' => 'create branch',
                'title' => 'Branch'
            ],
            [
                'name' => 'edit branch',
                'title' => 'Branch'
            ],
            [
                'name' => 'list branch',
                'title' => 'Branch'
            ],
            [
                'name' => 'delete branch',
                'title' => 'Branch'
            ],
            [
                'name' => 'create role',
                'title' => 'Role'
            ],
            [
                'name' => 'edit role',
                'title' => 'Role'
            ],
            [
                'name' => 'list role',
                'title' => 'Role'
            ],
            [
                'name' => 'delete role',
                'title' => 'Role'
            ],
        ];
        foreach($permissions as $permission){
            Permission::create($permission);
        }
    }
}
