<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemSize;
use App\Models\FullCount;
use App\Models\Weight;
use App\Http\Resources\ItemResource;
use Illuminate\Support\Facades\DB;
use Auth;

class SearchItemController extends BaseController{
    public function search(Request $request){
        $branch_id=auth()->user()->branch_id;
        $converted_inventory_items=[];
        $converted_all_items=[];
        $data=$request->name;
        $inventory_items=[];
        $user_items=[];
        $item_size_ids=[];
        
        if($data){
           
            $fullcounts=DB::table('full_counts')->where('branch_id',$branch_id)->get();
            $item_ids[]=DB::table('full_counts')->where('branch_id',$branch_id)->pluck('item_id');
            
            if($fullcounts->count()){
                foreach($fullcounts as $fullcount){
                    if(str_contains($fullcount->size,'x')){
                        $sizeArray=explode("x",$fullcount->size);
                        $packaging_qty=$sizeArray[0];
                        //$countable unitSize value is 18ml
                        $countable_unitSize=$sizeArray[1];
                        preg_match_all('!\d+!', $countable_unitSize , $matches);
                        $countable_unit=$matches[0][0];
                        $pattern="/{$countable_unit}/i";
                        $countable_size=trim(preg_replace($pattern,'',$countable_unitSize));
                        
                        //new method
                        $item_size_ids[]=ItemSize::where('item_id',$fullcount->item_id)
                        ->where('countable_unit',$countable_unit)
                        ->where('countable_size',$countable_size)
                        ->pluck('id');
                    }
                    else{
                        $countable_unit=1;
                        $countable_size="";
                        if(!str_contains($fullcount->size,"-")){
                            preg_match_all('!\d+!', $fullcount->size, $matches);
                            $countable_unit= isset($matches[0][0]) ? $matches[0][0] : "" ;
                            $pattern="/{$countable_unit}/i";
                            $countable_size=trim(preg_replace($pattern,'',$fullcount->size));
                            
                        }
                      
                       
                       //new method
                        $item_size_ids[]=ItemSize::where('item_id',$fullcount->item_id)
                        ->where('countable_unit',$countable_unit)
                        ->where('countable_size',$countable_size)
                        ->pluck('id');
                        if(count($item_size_ids)==0){
                            //packaging exist but no size option
                            $item_size_ids[]=ItemSize::where('item_id',$fullcount->item_id)
                            ->where('sizeoption','no')
                            ->pluck('id');
                        }
                    }
                  
                }
               
            }
            
            
            $weights=DB::table('weights')->where('branch_id',$branch_id)->get();
            $item_ids[]=DB::table('weights')->where('branch_id',$branch_id)->pluck('item_id');
            if($weights->count()){
                foreach($weights as $weight){
                    if(str_contains($weight->size,'x')){
                        
                        $sizeArray=explode("x",$weight->size);
                        $packaging_qty=$sizeArray[0];
                        //$countable unitSize value is 18ml
                        $countable_unitSize=$sizeArray[1];
                        preg_match_all('!\d+!', $countable_unitSize , $matches);
                        $countable_unit=$matches[0][0];
                        $pattern="/{$countable_unit}/i";
                        $countable_size=trim(preg_replace($pattern,'',$countable_unitSize));
                        

                        //new method
                        $item_size_ids[]=ItemSize::where('item_id',$fullcount->item_id)
                        ->where('countable_unit',$countable_unit)
                        ->where('countable_size',$countable_size)
                        ->pluck('id')->toArray();
                    }
                    else{
                        $countable_unit=1;
                        $countable_size="";
                        if(!str_contains($fullcount->size,"-")){
                            preg_match_all('!\d+!', $weight->size, $matches);
                            $countable_unit=$matches[0][0];
                        
                            $pattern="/{$countable_unit}/i";
                            $countable_size=trim(preg_replace($pattern,'',$weight->size));
                        }
                    
                        //new method
                        $item_size_ids[]=ItemSize::where('item_id',$weight->item_id)
                        ->where('countable_unit',$countable_unit)
                        ->where('countable_size',$countable_size)
                        ->pluck('id');

                        if(count($item_size_ids)==0){
                            //packaging exist but no size option
                            $item_size_ids[]=ItemSize::where('item_id',$fullcount->item_id)
                            ->where('sizeoption','no')
                            ->pluck('id');
                        }
                    }
                   
                       
                }
                
            }
           
            $combined_item_ids=[];
            if(count($item_ids)){
                foreach($item_ids as $item_id_array){
                    
                    foreach($item_id_array as $item_id){
                        array_push($combined_item_ids,$item_id);
                    }
                }
            }
            $combined_item_size_ids=[];
            if(count($item_size_ids)){
                foreach($item_size_ids as $item_size_id_array){
                    
                    foreach($item_size_id_array as $item_size_id){
                        array_push($combined_item_size_ids,$item_size_id);
                    }
                }
            }
            //if package_id from fullcount and weight work correctly, runtime can be reduced

            $inventory_items=DB::table('items')->join('classes','items.class_id','=','classes.id')
                        ->join('categories','items.category_id','=','categories.id')
                        ->join('item_sizes','items.id','=','item_sizes.item_id')
                        ->join('item_packages','item_sizes.id','=','item_packages.item_size_id')
                        ->select('items.*','item_sizes.countable_unit','item_sizes.countable_size',
                        'item_sizes.id as item_size_id','item_sizes.package_status','item_sizes.sizeoption',
                        'item_packages.package_barcode','item_packages.unit_from','item_packages.unit_to',
                        'item_packages.qty as item_package_qty','item_packages.id as item_package_id',
                        'classes.name as class_name','categories.name as category_name')
                        ->where(function($query) use($data){
                        $query->where('items.name','LIKE',"%{$data}%")
                        ->orWhere('item_packages.package_barcode','LIKE',"%{$data}%");
                        })
                        ->whereIn('items.id',$combined_item_ids)
                        // ->whereIn('item_packages.item_size_id',$combined_item_size_ids)
                        ->get();

           
            $all_items=DB::table('items')->join('classes','items.class_id','=','classes.id')
            ->join('categories','items.category_id','=','categories.id')
            ->join('item_sizes','items.id','=','item_sizes.item_id')
            ->join('item_packages','item_sizes.id','=','item_packages.item_size_id')
            ->select('items.*','item_sizes.countable_unit','item_sizes.countable_size',
            'item_sizes.id as item_size_id','item_sizes.package_status','item_sizes.sizeoption',
            'item_packages.package_barcode','item_packages.unit_from','item_packages.unit_to',
            'item_packages.qty as item_package_qty','item_packages.id as item_package_id',
            'classes.name as class_name','categories.name as category_name')
            ->where('items.name','LIKE',"%{$request->name}%")
            ->orWhere('item_packages.package_barcode','LIKE',"%{$request->name}%")->get();
        
        
        if(count($inventory_items)>0){
            foreach($inventory_items as $inventory_item){
                $converted_inventory_items[]=new ItemResource($inventory_item);
            }
        }
       
        if($all_items->count()>0){
            foreach($all_items as $all_item){
            //package status yes but should include if unit_from and unit_to are different
            $converted_all_items[]=new ItemResource($all_item);
              
            }
        }
        
        $result;
        $result['carryingProducts']=$converted_inventory_items;
        $result['notCarriedProducts']=$converted_all_items;
        return $result;
    }
    }
}