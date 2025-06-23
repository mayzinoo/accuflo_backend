<?php

namespace App;

// added by gian
// If the is a better way to add global vars, Please relocate this.
// do not put in .env and helpers
class GlobalConstants
{
    const WEIGHT_UOM = [
        0 => 'ml',
        -1 => 'L',
        -2 => 'oz',
        -3 => 'cL',
        -4 => '100-mL',
        -5 => 'hL',
        -6 => '30-mL',
        -7 => '25-mL',
        -8 => '45-mL',
    ];

    const CHANGE_STATUS = [
        0 =>  array('title' => "N", 'desc' => 'New Recipe', 'color' => 'danger'),
        1 =>  array('title' => "", 'desc' => '', 'color' => ''),
        2 =>  array('title' => "P", 'desc' => 'Price changed in this period', 'color' => 'success'),
        3 =>  array('title' => "M", 'desc' => 'Recipe modified in this period', 'color' => 'warning'),
        4 =>  array('title' => "MP", 'desc' => 'Price & Recipe modified in this period', 'color' => 'primary'),
    ];

    const BATCHMIX_UOM = [
        0 => 'ml',
        1 => 'L',
        2 => 'oz',
        3 => 'cL',
        4 => '100-mL',
        5 => 'hL',
        6 => '30-mL',
        7 => '25-mL',
        8 => '45-mL',
    ];

    const BATCHMIX_UD = [
        0 =>  "SERVING",
        1 =>  "PORTION",
        2 =>  "EACH",
        3 =>  "BATCH",
        4 =>  "UNIT"
    ];


    const BATCHMIX_WEIGHT_UNIT = [
        0 =>  "g",
        1 =>  "kg",
        2 =>  "dry oz",
        3 =>  "lb",
        4 =>  "mg"
    ];

    const BATCHMIX_VOLUME_UNIT = [
        0 =>  "ml",
        1 =>  "L",
        2 =>  "oz",
        3 =>  "imp oz",
        4 =>  "cL",
        5 =>  "100-mL",
        6 =>  "US gal",
        7 =>  "US tbsp",
        8 =>  "US tsp",
        9 =>  "US cup",
        10 =>  "US quart",
        11 =>  "imp gal",
        12 =>  "hL",
        13 =>  "30-mL",
        14 =>  "25-mL",
        15 =>  "45-mL"
    ];

    const INVOICE_DUE_DATE = [
        0 =>  "Same as the Invoice Date",
        1 =>  "Of the Following Month",
        2 =>  "Day(s) After the Invoive Date",
        3 =>  "Week(s) After the Invoive Date",
        4 =>  "Month(s) After the Invoive Date"
    ];

    const COUNTRY = [
        0 =>  "Singapore"
    ];

    const UNITS = [
        'BOTTLE' => 'BOTTLE',
        'PVC BTL' => 'PVC BTL',
        'CAN' => 'CAN',
        'KEG' => 'KEG',
        'BARREL' => 'BARREL',
        'EACH' => 'EACH'
    ];

    const PERIOD_STATUS = [
        'close' => 0,
        'open' => 1
    ];

    const USER_TYPES = [
        'super_admin' => 'Super Admin',
        'client' => 'Client'
    ];

    const PRICE_LEVEL_TYPE = [
        0 => 'REGULAR',
        1 => 'SPILL',
        2 => 'COMPS',
        3 => 'OTHERS'
    ];

    const COUNTABLE_UNIT_ID = [
        'ml' => 'ml',
        'L' => 'L',
        // 'oz' => 'oz',
        // 'cL' => 'cL',
        // '100-mL' => '100-mL',
        // 'hL' => 'hL',
        // '30-mL' => '30-mL',
        // '25-mL' => '25-mL',
        // '45-mL' => '45-mL',
    ];

    const EMPTY_WEIGHT_ID = [
        'g' =>  'g',
        'kg' =>  'kg',
        // 'dry oz' =>  'dry oz',
        // 'lb' =>  'lb',
        // 'mg' =>  'mg'
    ];

    const FULL_WEIGHT_ID = [
        'g' =>  'g',
        'kg' =>  'kg',
        // 'dry oz' =>  'dry oz',
        // 'lb' =>  'lb',
        // 'mg' =>  'mg'
    ];

    const DENSITY_WEIGHT_ID = [
        'g' =>  'g',
        'kg' =>  'kg',
        // 'dry oz' =>  'dry oz',
        // 'lb' =>  'lb',
        // 'mg' =>  'mg'
    ];

    const DENSITY_UNIT_ID = [
        'ml' => 'ml',
        'L' => 'L',
        // 'oz' => 'oz',
        // 'cL' => 'cL',
        // '100-mL' => '100-mL',
        // 'hL' => 'hL',
        // '30-mL' => '30-mL',
        // '25-mL' => '25-mL',
        // '45-mL' => '45-mL',
    ];
    const CLASS_LIQUOR_TYPE = [
        'Liquor' => 'Liquor',
        'Beer'   => 'Beer',
        'Miscellaneous' => 'Miscellaneous'
    ];

    const PRICE_LEVELS = [
        1 => 'Regular',
        2 => 'Spillage'
    ];

    const REPORT_TYPE = [
        'Category' => 'Category',
        'Quality'   => 'Quality',
    ];

    const REPORT_TYPE_1 = [
        'Detailed' => 'Detailed',
        'Summary'   => 'Summary',
    ];
}
