<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Section;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Shelf;
use App\Models\FullCount;
use App\Models\Weight;
use App\Models\Item;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use App\Models\Recipe;
use App\Models\RecipeIngredients;
use App\Models\RecipeSale;
use App\Models\Period;
use App\Models\Branch;
use App\Models\Summary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class DashboardController extends Controller
{
    public function index(Request $request) 
    {   
        /* start count accept and reject from mobile */
        //default branch id will be used as soon as user login to web
        // $default_branch_id=0;
        // if(Auth::user()->role == "super_admin" || Auth::user()->role == "admin"){
        //     $branches = Branch::get();
        //     $len=$branches->count();
        //     if($len>0){
        //         $default_branch_id=$branches[$len-1]->id;
        //     }
            
        // }else{
        //     $default_branch_id =  Auth::user()->branch_id;
        // }
        
        // $branch_id=isset($request->branch_id) ? [$request->branch_id] : (session()->get('branch_id') ? session()->get('branch_id') : $default_branch_id); 
        $branch_id = session()->get('branch_id') ? session()->get('branch_id') : auth()->user()->branch_id ;

        $data['items']=[];
        $data['sessions']=DB::table('sessions')->selectRaw
        ('sum(current_period_count) as total_count, sum(current_period_weight) as total_weight,device,created_at, count(id) as row_count')
        ->where('branch_id',$branch_id)->groupBy('created_at')->orderByDesc('created_at')->get();
        $data['sessionDetails']=DB::table('sessions')
        ->where('branch_id',$branch_id)->get()->groupBy('created_at');
       
        if($data['sessions']->count()){
            $item_ids=Session::where('branch_id',$branch_id)->pluck('item_id');
            $data['items']=DB::table('items')->join('item_sizes','items.id','=','item_sizes.item_id')
            ->join('item_packages','item_sizes.id','=','item_packages.item_size_id')
            ->select('items.*','item_sizes.*','item_packages.unit_from','item_packages.unit_to',
            'item_packages.qty','item_packages.package_barcode','item_packages.id as package_id')
            ->whereIn('items.id',$item_ids)->get()->keyBy('package_id');           
        }
        /* end count accept and reject from mobile */

        /* start management report */
        $branch_id = session()->get('branch_id') ? session()->get('branch_id') : auth()->user()->branch_id ;
        $period_id = session()->get('period_id') ? session()->get('period_id') : Period::where('branch_id', $branch_id)->value('id');
        $current_period_end_date = Period::where('branch_id', $branch_id)->where('id', $period_id)->value('end_date');
        $previous_period_id = 0;
        if($branch_id && $period_id && $current_period_end_date){
            $previous_period_id = Period::where('branch_id', $branch_id)->where('id', '!=',$period_id)->whereDate('start_date' , '<', $current_period_end_date)->orderBy('id', 'desc')->value('id');
        }     
        
        $current_period = Summary::getOnHand($period_id, $branch_id);
        $previous_period = Summary::getOnHand($previous_period_id, $branch_id);
        $current_used = Summary::getUsedCost($period_id, $branch_id, $current_period['on_hand_cost'], $previous_period['on_hand_cost']);
        $current_sale = Summary::getSaleCost($period_id, $branch_id);
        //get item pie chart data
        $pie_chart_class = Summary::getPieChartClass($current_period);
        $used_pie_chart_class = Summary::getUsedPieChartClass($current_used['used_cost_by_item']);
        $sale_pie_chart_class = Summary::getSalePieChartClass($current_sale['sale_cost_by_item']);
        $missing = Summary::getMissing($current_period, $current_sale);
            
        $data['on_hand_cost'] = number_format($current_period['on_hand_cost'], 2);
        $data['used_cost'] = number_format($current_used['used_cost'], 2);
        $data['sale_cost'] = number_format($current_sale['sale_cost'], 2);
        $data['pie_chart_class'] = array_keys($pie_chart_class);
        $data['pie_chart_class_cost'] = array_values($pie_chart_class);
        $data['used_pie_chart_class'] = array_keys($used_pie_chart_class);
        $data['used_pie_chart_class_cost'] = array_values($used_pie_chart_class);
        $data['sale_pie_chart_class'] = array_keys($sale_pie_chart_class);
        $data['sale_pie_chart_class_cost'] = array_values($sale_pie_chart_class);
        $data['missing_items'] = $missing['missing_cost_by_item'];
        $data['loss_leader'] = $missing['loss_leader'];
        /* end management report */
        
        return view('dashboard', $data);
    }

    
    public function accept(Request $request){
        $period_id=session()->get('period_id');
        $branch_id=session()->get('branch_id');
        $sessions=Session::where('created_at',$request->created_at)->get();
    
        foreach($sessions as $session){
            if($session->current_period_count){
            
                $item_size=ItemSize::find($session->item_size_id);
                $size=$item_size->countable_unit.$item_size->countable_size;
           
                $item_package=ItemPackage::where('id',$session->item_package_id)->get();
                if($item_package->count()){
                    $item_package=$item_package[0];
                    if($item_package->unit_from != $item_package->unit_to){
                        $size = $item_package->qty. ' x '.$size;
                    }
                }
           
                $fullcount=FullCount::create([
                    'item_id'     => $session->item_id,
                    'period_id'   => $period_id,
                    'user_id'     => $session->user_id,
                    'branch_id'   => $branch_id,
                    'station_id' => $session->station_id,
                    'size'        => $size, 
                    'period_count' => $session->current_period_count,
                    'package_id' => $session->item_package_id,
                    'mobile_submit_time' => $session->submit_time
                ]);
           
            }
            else if($session->current_period_weight){
                // dd("else if");
                $section=Section::where('name',$session->section)->where('station_id',$session->station_id)->get()[0];
                
                $shelf=Shelf::where('station_id',$session->station_id)->where('section_id',$section->id)
                ->where('shelf_name',$session->shelf)->get()[0];
                $item_size=ItemSize::find($session->item_size_id);
                $size=$item_size->countable_unit.$item_size->countable_size;
                $item_package=ItemPackage::find($session->item_package_id);
                if($item_package->unit_from != $item_package->unit_to){
                    $size = $item_package->qty. ' x '.$size;
                }
           
                $weight=Weight::create([
                    'item_id' => $session->item_id,
                    'station_id'=> $session->station_id,
                    'section_id' => $section->id,
                    'shelf_id'  => $shelf->id,
                    'period_id' => $period_id,
                    'user_id' => $session->user_id,
                    'branch_id'   => $branch_id,
                    'unit_id' => $session->unit,
                    'weight'  => $session->current_period_weight,
                    'package_id' => $session->item_package_id,
                    'size'  => $size, 
                    'mobile_submit_time' => $session->submit_time
                ]);
        
            }
            $session->delete();
     
        } 
   
        return redirect()->route('dashboard')->with('success','Data inserted successfully.');   
  
    }

    public function reject(Request $request){
        
        Session::where('created_at',$request->created_at)->delete();
        
        return redirect()->route('dashboard')->with('success','Successfully reject.');

    }
}
