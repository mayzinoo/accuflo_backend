<?php

namespace App\Exports;

use App\Models\FullCount;
use App\Models\Item;
use App\Models\Period;
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

class SummaryVarianceExport implements FromView, WithColumnWidths, WithStyles, WithDefaultStyles, WithCustomStartCell, WithEvents, WithDrawings, WithTitle
{

    protected $excel_category_quality_id;
    protected $excel_detail_summary_id;

    public function __construct(String  $excel_category_quality_id, String $excel_detail_summary_id)
    {
        $this->excel_category_quality_id = $excel_category_quality_id;
        $this->excel_detail_summary_id = $excel_detail_summary_id;
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

                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('E1:K1');
                $sheet->setCellValue('E1', $user_name);

                $sheet->mergeCells('E2:K2');
                $sheet->setCellValue('E2', $file_date);

                $styleArray = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];

                $cellRange = 'A1:K1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);


                $cellRange1 = 'A2:K2'; // All headers

                $styleArray1 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray1);

                $cellRange2 = 'A3:O3'; // All headers

                $styleArray2 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray2);

                $cellRange3 = 'A4:K4';

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
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20
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

        if ((isset($this->excel_category_quality_id) && isset($this->excel_detail_summary_id))) {

            $item_id = FullCount::select('item_id')
                ->where('period_id', $period_id)
                ->where('branch_id', $branch_id)
                ->pluck('item_id')->toArray();

            $items = Item::whereIn('id',  $item_id)
                ->pluck('id');

            $classes = Item::whereIn('id',  $item_id)
                ->select('class_id')
                ->where('class_id', '>' , 0)
                ->groupBy('class_id')
                ->get();

            if (
                $this->excel_category_quality_id == 'Category' &&
                $this->excel_detail_summary_id == 'Summary'
            ) {
                $categories = Item::whereIn('id',  $item_id)
                    ->select('category_id')
                    ->groupBy('category_id')
                    ->get();
                $category_quality_status = 'Category';
                $detail_summary_status = 'Summary';
                $qualities = [];
            } else {
                $qualities = Item::whereIn('id',  $item_id)
                    ->select('quality_id')
                    ->groupBy('quality_id')
                    ->get();
                $category_quality_status = 'Quality';
                $detail_summary_status = 'Summary';
                $categories = [];
            }
        } else {
            $items = [];
            $classes = [];
            $categories = [];
            $qualities = [];
            $category_quality_status = '';
            $detail_summary_status = '';
        }

        return view('admin.report_variance.summaryexportexcel', [
            'items' => $items,
            'classes' => $classes,
            'categories' => $categories,
            'qualities' => $qualities,
            'category_quality_status' => $category_quality_status,
            'detail_summary_status' => $detail_summary_status,
        ]);
    }
}
