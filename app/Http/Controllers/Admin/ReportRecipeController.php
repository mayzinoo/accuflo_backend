<?php

namespace App\Http\Controllers\Admin;

use App\Exports\RecipeExport;
use PDF;
use App\GlobalConstants;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use App\Models\Period;
use App\Models\PriceLevel;
use App\Models\Recipe;
use App\Models\RecipeIngredients;
use App\Models\Station;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReportRecipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:recipes');
    }
    public function index(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
        $period = Period::where('id', 'LIKE', '%' . $period_id . '%')->get()[0];

        $station_id = isset($request->station_id) ? $request->station_id : 1;
        $stations = Station::get();
        $price_levels = PriceLevel::where([['station_id', $station_id], ['client_id', $period_id]])->get();
        //dd($price_levels);
        if (isset($request->station_id)) {
            $recipes = $this->Recipes($user_id, $period_id, $station_id);
        } else {
            $recipes = [];
        }

        return view('admin.report_recipe.index', compact('recipes', 'stations', 'station_id', 'price_levels'));
    }

    public function exportExcel(Request $request)
    {
        $branch_id = session()->get('branch_id');
        $user = Branch::where('id', $branch_id)->first();
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $file_name =  $user->name . ' - Drink Mix Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

        return Excel::download(new RecipeExport($request->excel_station_id), $file_name . '.xlsx');
    }

    public function exportPDF(Request $request)
    {

        $branch_id = session()->get('branch_id');
        $user = Branch::where('id', $branch_id)->first();
        $period_id = session()->get('period_id');
        $period = Period::where('id', $period_id)->get()[0];
        $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
        $new_start_date = explode("-", $start_date);
        $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
        $new_end_date = explode("-", $end_date);

        $station_id = $request->pdf_station_id;

        $price_levels = PriceLevel::where([['station_id', $station_id], ['client_id', $branch_id]])->get();

        $recipes = $this->Recipes($branch_id, $period_id, $station_id);

        $pdf = PDF::loadView('admin.report_recipe.exportpdf', compact('recipes', 'price_levels'));

        $file_name =  $user->name . ' - Drink Mix Report for ' . $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2] . ' .pdf ';

        return $pdf->download($file_name);
    }

    private function Recipes($branch_id, $period_id, $station_id)
    {
        $recipes = Recipe::where([['branch_id', $branch_id], ['station_id', $station_id], ['period_id', $period_id]])->get();
        $invoices = Invoice::where('period_id', $period_id)->where('branch_id', $branch_id)->get();
        $invoice_details = InvoiceDetails::whereIn('invoice_id', $invoices->pluck('id'))
            ->select('item_id', 'unit_price', 'purchase_package')
            ->get();
        foreach ($recipes as $recipe) {

            if ($invoice_details->isEmpty()) {
                foreach ($recipe->ingredients as $index => $item_ingredient) {
                    $per_price = 0;
                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                }
                $recipe['cost'] += $recipe->ingredients[$index]->cost;
            } else {

                foreach ($recipe->ingredients as $index => $item_ingredient) {

                    $invoice_details = InvoiceDetails::where('item_id', $item_ingredient->item_id)
                        ->select('item_id', 'unit_price', 'purchase_package')
                        ->get();

                    foreach ($invoice_details as $invoice_detail) {
                        $invoice_item_id = $invoice_detail->item_id;
                        $invoice_unit_price = $invoice_detail->unit_price;
                        $invoice_purchase_package = $invoice_detail->purchase_package;
                    }

                    if (
                        isset($invoice_item_id) &&
                        isset($invoice_unit_price) &&
                        isset($invoice_purchase_package)
                    ) {
                        $item_package = ItemPackage::where('id', $invoice_purchase_package)->get()[0];
                        $ingredient_item_size = ItemSize::where('item_id', $item_ingredient->item_id)->get()[0];
                        if ($item_ingredient->package_id > 0) {
                            $ingredient_item_package = ItemPackage::where('id', $item_ingredient->package_id)->get()[0];
                            if ($invoice_item_id == $item_ingredient->item_id) {
                                if (
                                    ($item_package->unit_to == 'BOTTLE' ||
                                        $item_package->unit_to == "PVC BTL" ||
                                        $item_package->unit_to == "CAN" ||
                                        $item_package->unit_to == "KEG" ||
                                        $item_package->unit_to == "BARREL") &&
                                    ($ingredient_item_package->unit_to == 'BOTTLE' ||
                                        $ingredient_item_package->unit_to == 'PVC BTL' ||
                                        $ingredient_item_package->unit_to == 'CAN' ||
                                        $ingredient_item_package->unit_to == 'KEG' ||
                                        $ingredient_item_package->unit_to == 'BARREL')
                                ) {
                                    $per_price = $invoice_unit_price;
                                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                    $this->getPureCost($invoice_unit_price, '', $recipe->sales, $item_ingredient->qty, $item_package->unit_to, $ingredient_item_package->unit_to);
                                } else if (
                                    ($item_package->unit_to == 'BOTTLE' ||
                                        $item_package->unit_to == "PVC BTL" ||
                                        $item_package->unit_to == "CAN" ||
                                        $item_package->unit_to == "KEG" ||
                                        $item_package->unit_to == "BARREL") &&
                                    ($ingredient_item_package->unit_to == 'BAG' ||
                                        $ingredient_item_package->unit_to == 'BLOCK' ||
                                        $ingredient_item_package->unit_to == 'CARTON' ||
                                        $ingredient_item_package->unit_to == 'CASE' ||
                                        $ingredient_item_package->unit_to == 'CRATE' ||
                                        $ingredient_item_package->unit_to == 'LOAF' ||
                                        $ingredient_item_package->unit_to == 'PACKAGE' ||
                                        $ingredient_item_package->unit_to == 'TRAY')
                                ) {
                                    $per_price = ($invoice_unit_price * $ingredient_item_package->qty)  * $item_ingredient->qty;
                                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                    $this->getPureCost($invoice_unit_price, $ingredient_item_package->qty, $recipe->sales, $item_ingredient->qty, $item_package->unit_to, $ingredient_item_package->unit_to);
                                } else if (
                                    ($item_package->unit_to == "BAG" ||
                                        $item_package->unit_to == "BLOCK" ||
                                        $item_package->unit_to == "BOX" ||
                                        $item_package->unit_to == "CARTON" ||
                                        $item_package->unit_to == "CASE" ||
                                        $item_package->unit_to == "CRATE" ||
                                        $item_package->unit_to == "LOAF" ||
                                        $item_package->unit_to == "PACKAGE" ||
                                        $item_package->unit_to == "TRAY"
                                    ) &&
                                    ($ingredient_item_package->unit_to == 'BOTTLE' ||
                                        $ingredient_item_package->unit_to == 'PVC BTL' ||
                                        $ingredient_item_package->unit_to == 'CAN' ||
                                        $ingredient_item_package->unit_to == 'KEG' ||
                                        $ingredient_item_package->unit_to == 'BARREL')
                                ) {
                                    $per_price = ($invoice_unit_price / $item_package->qty);
                                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                    $this->getPureCost($invoice_unit_price, $item_package->qty, $recipe->sales, $item_ingredient->qty, $item_package->unit_to, $ingredient_item_package->unit_to);
                                } else if (
                                    ($item_package->unit_to == "BAG" ||
                                        $item_package->unit_to == "BLOCK" ||
                                        $item_package->unit_to == "BOX" ||
                                        $item_package->unit_to == "CARTON" ||
                                        $item_package->unit_to == "CASE" ||
                                        $item_package->unit_to == "CRATE" ||
                                        $item_package->unit_to == "LOAF" ||
                                        $item_package->unit_to == "PACKAGE" ||
                                        $item_package->unit_to == "TRAY"
                                    ) &&
                                    ($ingredient_item_package->unit_to == 'BAG' ||
                                        $ingredient_item_package->unit_to == 'BLOCK' ||
                                        $ingredient_item_package->unit_to == 'BOX' ||
                                        $ingredient_item_package->unit_to == 'CARTON' ||
                                        $ingredient_item_package->unit_to == "CASE" ||
                                        $ingredient_item_package->unit_to == 'CRATE' ||
                                        $ingredient_item_package->unit_to == 'LOAF' ||
                                        $ingredient_item_package->unit_to == 'PACKAGE' ||
                                        $ingredient_item_package->unit_to == 'TRAY')
                                ) {
                                    $per_price = $invoice_unit_price;
                                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                    $this->getPureCost($invoice_unit_price, '', $recipe->sales, $item_ingredient->qty, $item_package->unit_to, $ingredient_item_package->unit_to);
                                }
                            } else {
                                $per_price = 0;
                                $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                foreach ($recipe->sales as $sale) {
                                    $pure_cost = 0;
                                    $sale->pure_cost = round($pure_cost);
                                }
                            }
                        } else {
                            if ($invoice_item_id == $item_ingredient->item_id) {
                                $countable_unit = $ingredient_item_size->countable_unit;
                                $countable_size = $ingredient_item_size->countable_size;
                                $uom = $item_ingredient->uom_text;
                                $unit_price = $invoice_unit_price;
                                $change_countable_size = $this->ChangeCountableSize($countable_size, $countable_unit, $uom, $unit_price);
                                if (($item_package->unit_to == 'BOTTLE' ||
                                    $item_package->unit_to == "PVC BTL" ||
                                    $item_package->unit_to == "CAN" ||
                                    $item_package->unit_to == "KEG" ||
                                    $item_package->unit_to == "BARREL")) {
                                    $per_price = $change_countable_size  * $item_ingredient->qty;
                                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                } else {
                                    $per_price = ($change_countable_size / $item_package->qty)  * $item_ingredient->qty;
                                    $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                                }

                                $this->getPureCost($change_countable_size, $item_package->qty, $recipe->sales, $item_ingredient->qty, $item_package->unit_to, '');
                            } else {
                                $per_price = 0;
                                $recipe->ingredients[$index]->cost = sprintf("%.2f", $per_price);
                            }
                        }
                    }


                    $recipe['cost'] += $recipe->ingredients[$index]->cost;
                }
            }
        }

        return $recipes;
    }

    private function ChangeCountableSize($countable_size, $countable_unit, $item_ingredient_uom, $invoice_detail_unit_price)
    {
        if ($countable_size == 'ml' && $item_ingredient_uom == 'ml') {
            $change_countable_size = $invoice_detail_unit_price / $countable_unit;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == 'L') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 1000;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == 'oz') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 29.5735295625;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == 'cL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 10;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 100;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == 'hL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 100000;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 30;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 25;
        } else if ($countable_size == 'ml' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 45;
        }

        if ($countable_size == 'L' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 1000;
        } else if ($countable_size == 'L' && $item_ingredient_uom == 'L') {
            $change_countable_size = $invoice_detail_unit_price / $countable_unit;
        } else if ($countable_size == 'L' && $item_ingredient_uom == 'oz') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 33.814022558919;
        } else if ($countable_size == 'L' && $item_ingredient_uom == 'cL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 100;
        } else if ($countable_size == 'L' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 0.1;
        } else if ($countable_size == 'L' && $item_ingredient_uom == 'hL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 100;
        } else if ($countable_size == 'L' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 0.03;
        } else if ($countable_size == 'L' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 0.025;
        } else if ($countable_size == 'L' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) * 0.045;
        }

        if ($countable_size == 'oz' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 29.5735296;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == 'L') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 0.0295735296875;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == 'oz') {
            $change_countable_size = $invoice_detail_unit_price / $countable_unit;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == 'cL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 2.95735296;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 29.5735296) * 100;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == 'hL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 0.00029574;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 29.5735296) * 30;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 29.5735296) * 25;
        } else if ($countable_size == 'oz' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 29.5735296) * 45;
        }

        if ($countable_size == 'cL' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 10;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == 'L') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 0.01;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == 'oz') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 0.33814;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == 'cL') {
            $change_countable_size = $invoice_detail_unit_price / $countable_unit;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 10) * 100;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == 'hL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 0.0001;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 10) * 30;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 10) * 25;
        } else if ($countable_size == 'cL' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 10) * 45;
        }

        if ($countable_size == '100-mL' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == 'L') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == 'oz') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == 'cL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit);
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == 'hL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 30) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 25) / 100;
        } else if ($countable_size == '100-mL' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 45) / 100;
        }

        if ($countable_size == 'hL' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 100000;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == 'L') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 100;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == 'oz') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 3381.4022701843;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == 'cL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 10000;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 100000) * 100;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == 'hL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit);
        } else if ($countable_size == 'hL' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 100000) * 30;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 100000) * 25;
        } else if ($countable_size == 'hL' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) / 100000) * 45;
        }

        if ($countable_size == '30-mL' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == 'L') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == 'oz') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == 'cL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == '100-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 100) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == 'hL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit);
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 25) / 30;
        } else if ($countable_size == '30-mL' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 45) / 30;
        }

        if ($countable_size == '25-mL' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == 'L') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == 'oz') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == 'cL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == '100-mL') {
            $change_countable_size =  (($invoice_detail_unit_price / $countable_unit) * 100) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == 'hL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 30) / 25;
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit);
        } else if ($countable_size == '25-mL' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 45) / 25;
        }

        if ($countable_size == '45-mL' && $item_ingredient_uom == 'ml') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == 'L') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == 'oz') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == 'cL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == '100-mL') {
            $change_countable_size =  (($invoice_detail_unit_price / $countable_unit) * 100) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == 'hL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == '30-mL') {
            $change_countable_size = (($invoice_detail_unit_price / $countable_unit) * 30) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == '25-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit) / 45;
        } else if ($countable_size == '45-mL' && $item_ingredient_uom == '45-mL') {
            $change_countable_size = ($invoice_detail_unit_price / $countable_unit);
        }

        return $change_countable_size;
    }

    private function getPureCost($invoice_detail_unit_price, $item_package_qty, $sales, $item_ingredient_qty, $item_package_unit_to, $ingredient_item_package_unit_to)
    {

        foreach ($sales as $index => $sale) {
            if (
                ($item_package_unit_to == 'BOTTLE' ||
                    $item_package_unit_to == "PVC BTL" ||
                    $item_package_unit_to == "CAN" ||
                    $item_package_unit_to == "KEG" ||
                    $item_package_unit_to == "BARREL") &&
                ($ingredient_item_package_unit_to == 'BOTTLE' ||
                    $ingredient_item_package_unit_to == 'PVC BTL' ||
                    $ingredient_item_package_unit_to == 'CAN' ||
                    $ingredient_item_package_unit_to == 'KEG' ||
                    $ingredient_item_package_unit_to == 'BARREL')
            ) {
                if ($sale->price == 0) {
                    $pure_cost = 0;
                    $sales[$index]->pure_cost = round($pure_cost);
                } else {
                    $pure_cost = (($invoice_detail_unit_price / $sale->price) * 100) * $item_ingredient_qty;
                    $sales[$index]->pure_cost = round($pure_cost);
                }
            } else if (
                ($item_package_unit_to == 'BOTTLE' ||
                    $item_package_unit_to == "PVC BTL" ||
                    $item_package_unit_to == "CAN" ||
                    $item_package_unit_to == "KEG" ||
                    $item_package_unit_to == "BARREL") &&
                ($ingredient_item_package_unit_to == 'BAG' ||
                    $ingredient_item_package_unit_to == 'BLOCK' ||
                    $ingredient_item_package_unit_to == 'CARTON' ||
                    $ingredient_item_package_unit_to == 'CASE' ||
                    $ingredient_item_package_unit_to == 'CRATE' ||
                    $ingredient_item_package_unit_to == 'LOAF' ||
                    $ingredient_item_package_unit_to == 'PACKAGE' ||
                    $ingredient_item_package_unit_to == 'TRAY')
            ) {
                if ($sale->price == 0) {
                    $pure_cost = 0;
                    $sales[$index]->pure_cost = round($pure_cost);
                } else {
                    $pure_cost = ((($invoice_detail_unit_price * $item_package_qty) / $sale->price) * 100) * $item_ingredient_qty;
                    $sales[$index]->pure_cost = round($pure_cost);
                }
            } else if (
                ($item_package_unit_to == "BAG" ||
                    $item_package_unit_to == "BLOCK" ||
                    $item_package_unit_to == "BOX" ||
                    $item_package_unit_to == "CARTON" ||
                    $item_package_unit_to == "CASE" ||
                    $item_package_unit_to == "CRATE" ||
                    $item_package_unit_to == "LOAF" ||
                    $item_package_unit_to == "PACKAGE" ||
                    $item_package_unit_to == "TRAY"
                ) &&
                ($ingredient_item_package_unit_to == 'BOTTLE' ||
                    $ingredient_item_package_unit_to == 'PVC BTL' ||
                    $ingredient_item_package_unit_to == 'CAN' ||
                    $ingredient_item_package_unit_to == 'KEG' ||
                    $ingredient_item_package_unit_to == 'BARREL')
            ) {
                if ($sale->price == 0) {
                    $pure_cost = 0;
                    $sales[$index]->pure_cost = round($pure_cost);
                } else {
                    $pure_cost = ((($invoice_detail_unit_price / $item_package_qty) / $sale->price) * 100) * $item_ingredient_qty;
                    $sales[$index]->pure_cost = round($pure_cost);
                }
            } else if (
                ($item_package_unit_to == "BAG" ||
                    $item_package_unit_to == "BLOCK" ||
                    $item_package_unit_to == "BOX" ||
                    $item_package_unit_to == "CARTON" ||
                    $item_package_unit_to == "CASE" ||
                    $item_package_unit_to == "CRATE" ||
                    $item_package_unit_to == "LOAF" ||
                    $item_package_unit_to == "PACKAGE" ||
                    $item_package_unit_to == "TRAY"
                ) &&
                ($ingredient_item_package_unit_to == 'BAG' ||
                    $ingredient_item_package_unit_to == 'BLOCK' ||
                    $ingredient_item_package_unit_to == 'BOX' ||
                    $ingredient_item_package_unit_to == 'CARTON' ||
                    $ingredient_item_package_unit_to == "CASE" ||
                    $ingredient_item_package_unit_to == 'CRATE' ||
                    $ingredient_item_package_unit_to == 'LOAF' ||
                    $ingredient_item_package_unit_to == 'PACKAGE' ||
                    $ingredient_item_package_unit_to == 'TRAY')
            ) {
                if ($sale->price == 0) {
                    $pure_cost = 0;
                    $sales[$index]->pure_cost = round($pure_cost);
                } else {
                    $pure_cost = (($invoice_detail_unit_price / $sale->price) * 100) * $item_ingredient_qty;
                    $sales[$index]->pure_cost = round($pure_cost);
                }
            } else {
                if ($sale->price == 0) {
                    $pure_cost = 0;
                    $sales[$index]->pure_cost = round($pure_cost);
                } else {
                    if (($item_package_unit_to == 'BOTTLE' ||
                        $item_package_unit_to == "PVC BTL" ||
                        $item_package_unit_to == "CAN" ||
                        $item_package_unit_to == "KEG" ||
                        $item_package_unit_to == "BARREL")) {
                        $pure_cost = (($invoice_detail_unit_price / $sale->price) * 100) * $item_ingredient_qty;
                        $sales[$index]->pure_cost = round($pure_cost);
                    } else {
                        $pure_cost = ((($invoice_detail_unit_price / $item_package_qty) / $sale->price) * 100) * $item_ingredient_qty;
                        $sales[$index]->pure_cost = round($pure_cost);
                    }
                }
            }
        }

        return $sales;
    }
}
