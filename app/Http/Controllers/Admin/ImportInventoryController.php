<?php

namespace App\Http\Controllers\Admin;

use App\Models\Classes;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemSize;
use App\Models\ItemPackage;
use App\Models\Station;
use App\Models\FullCount;
use App\Models\Section;
use App\Models\Shelf;
use App\Models\Weight;
use App\Models\Quality;
use App\Http\Controllers\Controller;
use App\Imports\ItemsImport;
use App\Imports\ItemSizesImport;
use App\Imports\StationsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportInventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:inventory upload via file'],['only' => ['index', 'import']]);
        $this->error=null;
    }
    public function index(){
        return view('admin.import.inventory');
    }

    public function import(Request $request){
       
        $branch_id=session()->get('branch_id');
        $period_id=session()->get('period_id');
        
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->inventory_file);
        $phpspreadsheet=$spreadsheet->getSheet(0);
        $total_rows=$phpspreadsheet->getHighestRow();
        //below ids will be used to delete inserted data if error occurr
        $first_item_id=0;
        $last_item_id=0;
        $first_full_count_id=0;
        $last_full_count_id=0;
        $first_weight_id=0;
        $last_weight_id=0;
        $data=[];
        $size_option='yes';
        $density_m_unit='g';
        $density_v_unit='ml';

        for($row=5;$row<=$total_rows;$row++){
            $productsDataAll = $phpspreadsheet-> rangeToArray ('A' . $row . ':' . 'K' . $row, NULL, TRUE, FALSE);
            
            $barcode=$productsDataAll[0][0];
            $item_name=$productsDataAll[0][2];
            $class_name=$productsDataAll[0][4];
            $category_name=$productsDataAll[0][5];
            $item_size_excel=$productsDataAll[0][6] ? $productsDataAll[0][6] : '';

            $full_weight=strtolower($productsDataAll[0][7]);
            $density=strtolower($productsDataAll[0][8]);
            
            $converted_full_weight=str_contains($full_weight,'na') ? '' : $full_weight;
                      
            //$on_hand value will used to save data into fullcount or weight table
            $on_hand=$productsDataAll[0][9]-0;
            $on_hand_uom=$productsDataAll[0][10];
            $full_weight=0;
            $empty_weight=0;
            $full_weight_size='';
            $countable_unit=0;
            $countable_size='';
            $package_status='no';
            $package_id=0;
           
            $item_size_id=0;

            $on_hand_fullcount=0;
            $on_hand_weight=0;
            $quantification='no';
            //if no item size check in db 
            //if no data found in db return error message
            
            if(preg_match('/[0-9999]*[.]+/', $on_hand, $matches)){
                //on hand is for weight
                $on_hand_weight=$on_hand;
                
            }
            else{
                $on_hand_fullcount=$on_hand;
            }
            
            if(count($data)>0){
                //check same item exist in data array
                if(isset($data[$item_name])){
                    
                    if(isset($data[$item_name]['barcode'][$barcode])){
                        //same item with same barcode exist
                        if($on_hand_weight != 0){
                            //start checking error
                            //<!--if on hand weight exist but no full weight, no density or no item size
                            
                            if($data[$item_name]['barcode'][$barcode]['full_weight']==null){
                                
                                $item_package=ItemPackage::where('package_barcode',$barcode)->get();
                                if($item_package->count()){
                                    $item_size=ItemSize::find($item_package[0]->item_size_id);
                                    if($item_size->full_weight==0 || $item_size->full_weight==null){
                                        
                                        $this->setError($barcode,'full weight');
                                        
                                    }
                                }else{
                                    $this->setError($barcode,'full weight');                                    
                                }
                                
                            }
                            if($data[$item_name]['barcode'][$barcode]['density']==null){
                                $item_package=ItemPackage::where('package_barcode',$barcode)->get();
                                if($item_package->count()){
                                    $item_size=ItemSize::find($item_package[0]->item_size_id);
                                    if($item_size->full_weight==0 || $item_size->full_weight==null){
                                        $this->setError($barcode,'density');
                                    }
                                }else{
                                    $this->setError($barcode,'density');
                                }
                                
                            }
                            if($data[$item_name]['barcode'][$barcode]['item_size']==null){
                                $item_package=ItemPackage::where('package_barcode',$barcode)->get();
                                if($item_package->count()){
                                    $item_size=ItemSize::find($item_package[0]->item_size_id);
                                    if($item_size->countable_unit==0 || $item_size->countable_unit==null){
                                        $this->setError($barcode,'item size');
                                    }
                                }else{
                                    $this->setError($barcode,'item size');
                                }
                            }
                            //end checking error -->
                            
                            if($data[$item_name]['barcode'][$barcode]['on_hand_weight'][0] >0){
                                //if on hand weight  > 0, that means on hand weight data exist
                                //2 rows with different on hand weight, but item name and barcode is same in imported excel
                              
                                array_push($data[$item_name]['barcode'][$barcode]['on_hand_weight'],$on_hand_weight);
                              
                            }
                            else{
                                //if on hand weight of barcode is 0, that means no on hand weight data before
                                $data[$item_name]['barcode'][$barcode]['on_hand_weight']=[$on_hand_weight];
                            }
                        }
                        if($on_hand_fullcount>0){
                            
                            if($data[$item_name]['barcode'][$barcode]['on_hand_fullcount'][0] >1){
                                //if on hand fullcount  > 1, that means on hand fullcount data exist
                                //2 fullcount data with same item name and same barcode
                              
                                array_push($data[$item_name]['barcode'][$barcode]['on_hand_fullcount'],[$on_hand_fullcount]);
                            }else{
                                //if on hand fullcount < 1, that means no on hand fullcount data before 
                                $data[$item_name]['barcode'][$barcode]['on_hand_fullcount']=[$on_hand_fullcount];
                            }
                            
                        }
                   
                    }
                    else{
                        //same item exist but same barcode doesn't exist
                        $data[$item_name]['barcode'][$barcode]=
                        ['item_size' => $item_size_excel, 
                        'full_weight' => $converted_full_weight, 
                        'density'=> $converted_density,
                        'on_hand_weight' => [$on_hand_weight],
                        'on_hand_fullcount' => [$on_hand_fullcount],
                        'on_hand_uom' => $on_hand_uom];
                        
                    }
                    
                }else{
                    //add new item to data Array since same item doesn't exist in data Array
                    $data[$item_name]=['class' => $class_name, 'category' => $category_name,
                    'barcode'=>[$barcode =>['item_size' => $item_size_excel, 
                                'full_weight' => $converted_full_weight, 
                                'density'=> $converted_density,
                                'on_hand_weight' => [$on_hand_weight],
                                'on_hand_fullcount' => [$on_hand_fullcount],
                                'on_hand_uom' => $on_hand_uom]]
                            ];
                }
            }else{
                
                $data[$item_name]=
                ['class' => $class_name, 'category' => $category_name,
                'barcode'=> [$barcode =>['item_size' => $item_size_excel, 
                            'full_weight' => $converted_full_weight, 
                            'density'=> $converted_density,
                            'on_hand_weight' => [$on_hand_weight],
                            'on_hand_fullcount' => [$on_hand_fullcount],
                            'on_hand_uom' => $on_hand_uom]]
                        ];
                        
            }
            
            // $station=Station::where('name',$station_name)->where('branch_id',$branch_id)->get();
            // if($station->count()==0){
            //     $station=Station::Create([
            //         'name'     => $station_name,
            //         'branch_id'  => $branch_id,
            //         'period_id'   => $period_id
            //     ]);
            // }else{
            //     $station=$station[0];
            // }
            
            //retrieve class if exist else create
          /*  if(!empty($class_name)){
                $class=Classes::firstOrCreate([
                    'name' => $class_name
                ]);
            
            
                //retrieve category if exist else create
                $category=Category::firstOrCreate([
                'name' => $category_name,
                'class_id' => $class->id
                ]);
           
                //get quality data
                $quality=Quality::firstOrCreate([
                    'name' => 'UNKNOWN',
                    'class_id' => $class->id
                ]);
           
                //item create

                $item=Item::where('name',$item_name)->get();
                if($item->count()==0){
                    $item=Item::Create([
                        'name' => $item_name,
                        'class_id'=> $class->id,
                        'category_id' => $category->id,
                        'quality_id' => $quality->id,
                        'branch_id' => $branch_id,
                        'period_id' => $period_id
                    ]);
    
                }
                else{
                    $item=$item[0];
                }
                //keep track of first item id and last item id
                if($row==5){
                    $first_item_id=$item->id;
                }else{
                    $last_item_id=$item->id;
                }
                        
                //item size & item package create
                if($item_size_excel){
                    
                    //item size 20 ml
                    preg_match_all('!\d+!', $item_size_excel, $matches);
                    $countable_unit=$matches[0][0];

                    $pattern="/{$countable_unit}/i";
                    $countable_size=trim(preg_replace($pattern,'',$item_size_excel));
                
                    $item_size_DB=ItemSize::where('item_id',$item->id)->where('countable_unit',$countable_unit)
                    ->where('countable_size',$countable_size)->get();
                
                    if(!empty($converted_full_weight)){
                        [$full_weight,$full_weight_size,$empty_weight]=$this->getWeights($converted_full_weight,$converted_density,$countable_unit);
                        
                    }
            
                    if($item_size_DB->count()==0){
                 
                        $item_size_DB=ItemSize::Create([
                            'item_id' => $item->id,
                            'countable_unit' => $countable_unit,
                            'countable_size' => $countable_size,
                            'empty_weight'   => $empty_weight, 
                            'empty_weight_size' => $full_weight_size, //if full weight size is Kg, empty weight size is also Kg
                            'full_weight'      => $full_weight,
                            'full_weight_size' => $full_weight_size,
                            'density'          => $converted_density,
                            'density_m_unit'   => $density_m_unit,
                            'density_v_unit'   => $density_v_unit,
                            'package_status'  => $package_status,
                            'sizeoption'     => $size_option
                        ]);
                    }else{
                        //if item size is created with 0 full weight before but update full weight everytime upload the same item
                        $item_size_DB=$item_size_DB[0];

                        if(!empty($converted_full_weight)){
                            
                            $item_size_DB->full_weight=$full_weight;
                            $item_size_DB->empty_weight=$empty_weight;
                            $item_size_DB->full_weight_size=$full_weight_size;
                            $item_size_DB->empty_weight_size=$full_weight_size;
                            $item_size_DB->save();
                        }
                        else{
                            $full_weight=$item_size_DB->full_weight;
                            $empty_weight=$item_size_DB->empty_weight;
                        }
                        
                    }

                    //item package create //need more data
                    $item_package_DB=ItemPackage::where('item_id',$item->id)->where('item_size_id',$item_size_DB->id)
                    ->where('package_barcode',$barcode)->get();
                    if($item_package_DB->count()==0){
                        $item_package_DB=ItemPackage::Create([
                        'item_id' => $item->id,
                        'item_size_id'=> $item_size_DB->id,
                        'qty'  => 1,
                        'unit_from' => $on_hand_uom,
                        'unit_to' => $on_hand_uom,
                        'package_barcode' => $barcode
                        ]);
                    }
                    else{
                        $item_package_DB=$item_package_DB[0];
                    }
                    $package_id=$item_package_DB->id;

                    $full_weight=$item_size_DB->full_weight;
                    $empty_weight=$item_size_DB->empty_weight;
                    $full_weight_size=$item_size_DB->full_weight_size;
                    $item_size_excel=$item_size_excel;

                }else{ //empty item size
                   
                    //item package create
                    $item_package_DB=ItemPackage::where('item_id',$item->id)
                    ->where('package_barcode',$barcode)->where('unit_to',$on_hand_uom)->get();
                    if($item_package_DB->count()==0){
                      //since empty item size, so use item_size_id 0

                      $item_package_DB=ItemPackage::Create([
                        'item_id' => $item->id,
                        'item_size_id'=> 0,
                        'qty'  => 1,
                        'unit_from' => $on_hand_uom,
                        'unit_to' => $on_hand_uom,
                        'package_barcode' => $barcode
                      ]);

                    }else{
                        $item_package_DB=$item_package_DB[0];
                    }
                    $package_id=$item_package_DB->id;
                     
                }
            }
            else{
                //class name is blank in excel
                
                $item=Item::where('name',$item_name)->get();
                if($item->count()==0){
                    //class id 0 since no class name is given
                    
                    //create new item
                    $item=Item::Create([
                        'name' => $item_name,
                        'class_id'=> 0,
                        'category_id' => 0,
                        'quality_id' => 0,
                        'branch_id' => $branch_id,
                        'period_id' => $period_id
                    ]);
                    
                    if(!empty($item_size_excel)){
                        //create new item size
                        [$countable_unit,$countable_size]=$this->getCountableUnitSize($item_size_excel);
                            
                        if(!empty($converted_full_weight)){
                            [$full_weight,$full_weight_size,$empty_weight]=$this->getWeights($converted_full_weight,$converted_density,$countable_unit);
                        }
                        $item_size_DB=ItemSize::Create([
                            'item_id' => $item->id,
                            'countable_unit' => $countable_unit,
                            'countable_size' => $countable_size,
                            'empty_weight'   => $empty_weight, 
                            'empty_weight_size' => $full_weight_size, //if full weight size is Kg, empty weight size is also Kg
                            'full_weight'      => $full_weight,
                            'full_weight_size' => $full_weight_size,
                            'density'          => $converted_density,
                            'density_m_unit'   => $density_m_unit,
                            'density_v_unit'   => $density_v_unit,
                            'package_status'  => $package_status,
                            'sizeoption'     => $size_option
                        ]);
                        $item_size_id=$item_size_DB->id;

                    }
                    
                    //create new item package
                    $item_package_DB=ItemPackage::Create([
                        'item_id' => $item->id,
                        'item_size_id'=> $item_size_id,
                        'qty'  => 1,
                        'unit_from' => $on_hand_uom,
                        'unit_to' => $on_hand_uom,
                        'package_barcode' => $barcode
                    ]);
                }
                else{ //item already existed in db
                    $item=$item[0];
                    $item_package_DB=ItemPackage::where('item_id',$item->id)
                    ->where('package_barcode',$barcode)->get();
                    
                    if($item_package_DB->count()==0){
                        
                        $item_size_DB=ItemSize::where('item_id',$item->id)->get();
                        
                        if($item_size_DB->count()==0){
                            if(!empty($item_size_excel)){
                                //create new item size
                                [$countable_unit,$countable_size]=$this->getCountableUnitSize($item_size_excel);
                            
                                if(!empty($converted_full_weight)){
                                    [$full_weight,$full_weight_size,$empty_weight]=$this->getWeights($converted_full_weight,$converted_density,$countable_unit);
                                }
                                $item_size_DB=ItemSize::Create([
                                    'item_id' => $item->id,
                                    'countable_unit' => $countable_unit,
                                    'countable_size' => $countable_size,
                                    'empty_weight'   => $empty_weight, 
                                    'empty_weight_size' => $full_weight_size, //if full weight size is Kg, empty weight size is also Kg
                                    'full_weight'      => $full_weight,
                                    'full_weight_size' => $full_weight_size,
                                    'density'          => $converted_density,
                                    'density_m_unit'   => $density_m_unit,
                                    'density_v_unit'   => $density_v_unit,
                                    'package_status'  => $package_status,
                                    'sizeoption'     => $size_option
                                ]);
                               
                                $item_size_id=$item_size_DB->id;
                            }                          
                        }
                        else{
                            $item_size_DB=$item_size_DB[0];
                            $item_size_id=$item_size_DB->id;

                        }
                        
                        $item_package_DB=ItemPackage::Create([
                            'item_id' => $item->id,
                            'item_size_id'=> $item_size_id,
                            'qty'  => 1,
                            'unit_from' => $on_hand_uom, 
                            'unit_to' => $on_hand_uom,
                            'package_barcode' => $barcode
                          ]);
                    }else{
                        
                        $item_package_DB=$item_package_DB[0];
                        $item_size_DB=ItemSize::find($item_package_DB->item_size_id);
                        
                    }

                    //item size is not given before but inclue it now
                    if($item_size_DB==null & !empty($item_size_excel)){
                        
                        [$countable_unit,$countable_size]=$this->getCountableUnitSize($item_size_excel);
                            
                        if(!empty($converted_full_weight)){
                            [$full_weight,$full_weight_size,$empty_weight]=$this->getWeights($converted_full_weight,$converted_density,$countable_unit);
                        }

                        $item_size_DB=ItemSize::Create([
                            'item_id' => $item->id,
                            'countable_unit' => $countable_unit,
                            'countable_size' => $countable_size,
                            'empty_weight'   => $empty_weight, 
                            'empty_weight_size' => $full_weight_size, //if full weight size is Kg, empty weight size is also Kg
                            'full_weight'      => $full_weight,
                            'full_weight_size' => $full_weight_size,
                            'density'          => $converted_density,
                            'density_m_unit'   => $density_m_unit,
                            'density_v_unit'   => $density_v_unit,
                            'package_status'  => $package_status,
                            'sizeoption'     => $size_option
                        ]);
                        $item_size_id=$item_size_DB->id;
                    }
                    else if($item_size_DB!=null){
                        
                        $item_size_DB=$item_size_DB;
                        if(!empty($item_size_excel)){
                            [$countable_unit,$countable_size]=$this->getCountableUnitSize($item_size_excel);
                            
                            $item_size_DB->countable_unit=$countable_unit;
                            $item_size_DB->countable_size=$countable_size;
                            $item_size_DB->save();
                            if(!empty($converted_full_weight)){
                                [$full_weight,$full_weight_size,$empty_weight]=$this->getWeights($converted_full_weight,$converted_density,$countable_unit);
                                
                                $item_size_DB->full_weight=$full_weight;
                                $item_size_DB->empty_weight=$empty_weight;
                                $item_size_DB->full_weight_size=$full_weight_size;
                                $item_size_DB->empty_weight_size=$full_weight_size;
                                $item_size_DB->save();
                            }
                        }
                        
                    }
    
                    $package_id=$item_package_DB->id;
                    
                    if($item_size_DB){
                        $full_weight=$item_size_DB->full_weight;
                        $empty_weight=$item_size_DB->empty_weight;
                        $full_weight_size=$item_size_DB->full_weight_size;
                        $item_size_excel=$item_size_DB->countable_unit.$item_size_DB->countable_size;
                    }
                    else{
                        
                        //checking item size exist with same item id;
                        $item_size_DB=ItemSize::where('item_id',$item->id)->get();
                        //no item size in db and on hand is floating point 
                        //would like to create weight but no full weight, ...
                        if(preg_match('/([0-9]{1,})\.([0-9]{2,2})/', $on_hand) != 0 && $item_size_DB->count()==0){ 
                            
                            $this->DeleteAlreadyInsertedData($first_item_id,$last_item_id,$first_full_count_id,$last_full_count_id,$first_weight_id,$last_weight_id);
                            $error_msg="Item Size is missing for {$item_name}. Please fix the excel file before importing again.";
                            return redirect()->route('inventory-upload.index')->with('warning',$error_msg);
                        }else{
                            //for full count excel row no need to assign full weight
                            if($item_size_DB->count()>0){
                                $full_weight=$item_size_DB[0]->full_weight;
                                $empty_weight=$item_size_DB[0]->empty_weight;
                                $full_weight_size=$item_size_DB[0]->full_weight_size;
                                $item_size_excel=$item_size_DB[0]->countable_unit.$item_size_DB[0]->countable_size;
                            }
                            
                        }
                    }
                                       
                }

                //keep track of first item id and last item id
                if($row==5){
                    $first_item_id=$item->id;
                }else{
                    $last_item_id=$item->id;
                }    
            
            } */
            //fullcount or weight create
            // if(preg_match('/([0-9]{1,})\.([0-9]{2,2})/', $on_hand) != 0){
               
                           
            //     if($full_weight==0 & empty($item_size_excel) & $on_hand){
                    
            //         $error_msg="Full Weight and Item Size is missing for {$item_name}. Please fix the excel file before importing again.";
            //     } 
            //     else if($full_weight==0 & $on_hand){
                    
            //         $error_msg="Full Weight value is missing for {$item_name}. Please fix the excel file before importing again.";
            //     }
            //     if(!empty($error_msg)){
            //         $this->DeleteAlreadyInsertedData($first_item_id,$last_item_id,$first_full_count_id,$last_full_count_id,$first_weight_id,$last_weight_id);                 
            //         return redirect()->route('inventory-upload.index')->with('warning',$error_msg);
            //     }

            //     $weight=round(($on_hand*($full_weight-$empty_weight))+$empty_weight);
                
            //     //add data to weight
            //     $weight=Weight::Create([
            //         'item_id'=> $item->id ,
            //         'station_id' => 0,
            //         'section_id' => 0,
            //         'shelf_id' => 0,
            //         'period_id' => $period_id,
            //         'branch_id' => $branch_id,
            //         'unit_id' => $full_weight_size,
            //         'weight' => $weight, 
            //         'package_id'=> $package_id,
            //         'size' => $item_size_excel
            //     ]);
            //     if($first_weight_id==0){
            //         $first_weight_id=$weight->id;
            //     }
            //     else{
            //         $last_weight_id=$weight->id;
            //     }

            // }else{
            //     //add data to fullcount
                
            //     $fullcount=FullCount::Create([
            //         'item_id' => $item->id ,
            //         'size'    => $item_size_excel,
            //         'period_id' => $period_id,
            //         'branch_id'   => $branch_id,
            //         'station_id' => 0,
            //         'period_count' => $on_hand,
            //         'package_id'  => $package_id
            //     ]);

            //     if($first_full_count_id==0){
            //         $first_full_count_id=$fullcount->id;
            //     }else{
            //         $last_full_count_id=$fullcount->id;
            //     }
            // } 
        }
        if($this->error){
            $total_error="";
            foreach($this->error as $barcode =>  $error){
                $sub_total_error="";
                $len=count($error['msg']);
                if($len==1){
                    $sub_total_error.=$error['msg'][0]. " ";
                }
                else{
                    
                    foreach($error['msg'] as $index => $msg){
                        if($index!=$len-1){
                            $sub_total_error.=$msg. " and ";
                        }else{
                            $sub_total_error.=$msg. " ";
                        }
                       
                    }
                }
                
                $total_error.=$sub_total_error. "is missing for ".$barcode;
                
            }
            return redirect()->route('inventory-upload.index')->with('warning',$total_error);
        }else{
            dd($data);
            foreach($data as $item_name => $result){
                $class_id=0;
                $category_id=0;
                if($result['class']){
                    $class=Classes::firstOrCreate([
                        'name' => $result['class']
                    ]);
                    $class_id=$class->id;
                }
                
                if($result['category']){
                    //retrieve category if exist else create
                    $category=Category::firstOrCreate([
                    'name' => $category_name,
                    'class_id' => $class_id
                    ]);
                    $category_id=$category->id;
                }
                //get quality data
                $quality=Quality::firstOrCreate([
                    'name' => 'UNKNOWN',
                    'class_id' => $class_id
                ]); 

                $unit_to='';
                $index=0;
                $quantification='no';
                foreach($result['barcode'] as $barcode => $item_data){
                    
                    //create new item if no data before else retrieve
                    $item=Item::firstOrCreate([
                        'name' => $item_name,
                        'class_id'=> $class_id,
                        'category_id' => $category_id,
                        'quality_id' => $quality->id,
                        'branch_id' => $branch_id,
                        'period_id' => $period_id
                    ]);
                    if($item_data['item_size']){
                        [$countable_unit,$countable_size]=$this->getCountableUnitSize($item_data['item_size']);
                        
                        $full_weight='';
                        $full_weight_size='';
                        $unit_from='';
                        if($item_data['full_weight']){
                             
                            [$full_weight,$full_weight_size,$empty_weight]=$this->getWeights($item_data['full_weight'],$item_data['density'],$countable_unit);
                        }
                        //check if miscellaneous class has both fullcount and weight
                        if($class_id==7 & $item_data['on_hand_weight'] & $item_data['on_hand_fullcount']){
                            $quantification='yes';
                        }
                        if($index>1){
                            //override unit_to for second and other barcode
                            dd($barcode, $item_data);
                            if($unit_to!=$item_data['on_hand_uom']){
                                $package_status='yes';
                            }
                        }else{
                            //set unit_from and unit_to for first barcode
                            $unit_from=$item_data['on_hand_uom'];
                            $unit_to=$item_data['on_hand_uom'];
                            $package_status='no';
                        }
                        
                        
                        $item_size_DB=ItemSize::firstOrCreate([
                            'item_id' => $item->id,
                            'countable_unit' => $countable_unit,
                            'countable_size' => $countable_size,
                            'empty_weight'   => $empty_weight, 
                            'empty_weight_size' => $full_weight_size, //if full weight size is Kg, empty weight size is also Kg
                            'full_weight'      => $full_weight,
                            'full_weight_size' => $full_weight_size,
                            'density'          => $item_data['density'],
                            'density_m_unit'   => $density_m_unit,
                            'density_v_unit'   => $density_v_unit,
                            'package_status'  => $package_status,
                            'sizeoption'      => $size_option,
                            'quantification'  => $quantification
                        ]);
                    }
                    else{
                        //no item size 
                        //first check item size data exist with same item
                        // if($unit_from !=)
                    }
                    //item size create
                    $index++;
                    
                }
                
               
            }
        }
    
        return redirect()->route('inventory-upload.index')->with('success','Inventory data imported successfully.');
    }

    public function DeleteAlreadyInsertedData($first_item_id,$last_item_id,$first_full_count_id,$last_full_count_id,$first_weight_id,$last_weight_id){
        //delete already inserted item data
        
        // if($first_item_id>0){
        //     $item_ids[0]=$first_item_id;
        //     $item_ids[1]=$last_item_id;
        //     $items=DB::table('items')->whereBetween('id',$item_ids)->get();
        //     foreach($items as $item){
        //         $item_packages=ItemPackage::where('item_id',$item->id)->get();
        //         foreach($item_packages as $item_package){
        //             $item_package->delete();
        //         }
        //         $item_sizes=ItemSize::where('item_id',$item->id)->get();
        //         foreach($item_sizes as $item_size){
        //             $item_size->delete();
        //         }
        //         $item=Item::find($item->id);
        //         $item->delete();                            
        //     }
        // }
        
        //delete already inserted full count data
        if($first_full_count_id>0){
            $fullcount_ids[0]=$first_full_count_id;
            $fullcount_ids[1]=$last_full_count_id;
            $fullcount_datas=DB::table('full_counts')->whereBetween('id',$fullcount_ids)->get();
            
            foreach($fullcount_datas as $fullcount_data){
                $fullcount=FullCount::find($fullcount_data->id);
                $fullcount->delete();
            }
        }
        //delete already inserted weight data
        if($first_weight_id>0){
            $weight_ids[0]=$first_weight_id;
            $weight_ids[1]=$last_weight_id;
            $weights=DB::table('weights')->whereBetween('id',$weight_ids)->get();
            foreach($weights as $weight_data){
                $weight=Weight::find($weight_data->id);
                $weight->delete();
            }
        }
    }

    public function getWeights($converted_full_weight,$converted_density,$countable_unit){
        $empty_weight=0;
        preg_match('/([a-zA-Z]*[a-zA-Z])/', $converted_full_weight, $matches);
        $full_weight_size=$matches[0];
                                
        $pattern="/{$full_weight_size}/i";
        $full_weight=trim(preg_replace($pattern,'',$converted_full_weight));
        if(!empty($converted_density)){
            $empty_weight=round($full_weight-($converted_density*$countable_unit));
                                
        }
        return [$full_weight,$full_weight_size,$empty_weight];
    }

    public function getCountableUnitSize($size){
        preg_match_all('!\d+!', $size, $matches);
        $countable_unit=$matches[0][0];
        
        $pattern="/{$countable_unit}/i";
        $countable_size=trim(preg_replace($pattern,'',$size));
        return [$countable_unit,$countable_size];
    }
    public function setError($barcode,$msg){
        
        if(isset($this->error[$barcode]['msg'])){
            array_push($this->error[$barcode]['msg'],$msg);
        }
        else{
            $this->error[$barcode]['msg']=[$msg];
        }
     
    }
}

