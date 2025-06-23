<?php

namespace App\Exports;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\ItemPackage;
use App\Models\ItemSize;
use App\Models\Period;
use App\Models\PriceLevel;
use App\Models\Recipe;
use App\Models\User;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Style;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithTitle;

class RecipeExport implements FromView, WithColumnWidths, WithStyles, WithDefaultStyles, WithCustomStartCell, WithEvents, WithDrawings, WithTitle
{

    protected $station_id;

    function __construct($station_id)
    {
        $this->station_id = $station_id;
    }

    public function title(): string
    {
        return 'Report';
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $branch_id = session()->get('branch_id');
                $user = Branch::where('id', $branch_id)->first();
                $user_name = $user->name;

                $period_id = session()->get('period_id');
                $period = Period::where('id', $period_id)->get()[0];
                $start_date = Carbon::parse($period->start_date)->format('M-d-Y');
                $new_start_date = explode("-", $start_date);
                $end_date = Carbon::parse($period->end_date)->format('M-d-Y');
                $new_end_date = explode("-", $end_date);

                $file_date = $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

                $sheet = $event->sheet;

                $sheet->mergeCells('E1:J1');
                $sheet->setCellValue('E1', $user_name);

                $sheet->mergeCells('E2:J2');
                $sheet->setCellValue('E2', $file_date);

                $styleArray = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];

                $cellRange = 'A1:J1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);


                $cellRange1 = 'A2:J2'; // All headers

                $styleArray1 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray1);

                $cellRange2 = 'A3:J3'; // All headers

                $styleArray2 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray2);

                $cellRange3 = 'A4:J4';

                $styleArray3 = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => Color::COLOR_WHITE]
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_BLACK],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $event->sheet->getDelegate()->getStyle($cellRange3)->applyFromArray($styleArray3);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(30);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('/images/excel_logo.png'));
        $drawing->setHeight(100);
        $drawing->setWidth(300);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 60,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'font' => ['size' =>  12],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_JUSTIFY,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'E1' => [
                'font' => ['bold' => true, 'size' =>  17],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            'E2' => [
                'font' => ['bold' => true, 'size' =>  13],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],

        ];
    }

    public function view(): View
    {

        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');

        $price_levels = PriceLevel::where([['station_id', $this->station_id], ['client_id', $branch_id]])->get();
       
        $recipes = $this->Recipes($user_id, $period_id, $this->station_id);

        return view('admin.report_recipe.exportexcel', [
            'recipes' => $recipes,
            'price_levels' => $price_levels
        ]);
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
                    $pure_cost = (($invoice_detail_unit_price / $sale->price) * 100);
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
