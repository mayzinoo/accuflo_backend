<?php

namespace App\Exports;

use App\GlobalConstants;
use App\Models\Batchmix;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Item;
use App\Models\Period;
use App\Models\User;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;
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

class PurchaseSummaryExport implements FromView, WithHeadings, WithColumnWidths, WithStyles, WithDefaultStyles, WithCustomStartCell, WithEvents, WithDrawings, WithTitle
{

    protected $form_date;
    protected $to_date;
    public static $count = 0;

    function __construct($form_date, $to_date)
    {
        $this->form_date = $form_date;
        $this->to_date = $to_date;
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

                $sheet->mergeCells('E1:G1');
                $sheet->setCellValue('E1', $user_name);

                $sheet->mergeCells('E2:G2');
                $sheet->setCellValue('E2', $file_date);

                $event->sheet->getDefaultRowDimension()->setRowHeight(30);  // All rows

                $styleArray = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];

                $cellRange = 'A1:G1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);


                $cellRange1 = 'A2:G2'; // All headers

                $styleArray1 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray1);

                $cellRange2 = 'A3:G3'; // All headers

                $styleArray2 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray2);

                $cellRange3 = 'A4:G4';

                $styleArray3 = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => Color::COLOR_WHITE]
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_BLACK],
                    ]
                ];

                $event->sheet->getDelegate()->getStyle($cellRange3)->applyFromArray($styleArray3);
            
                for ($i = 0; $i < Self::$count; $i++) {
                    $cellRange4 = 'A' . $i+5 . ':G' . $i+5;
                    $styleArray4 = [
                        'borders' => [
                            'outline' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => 'dee2e6'],
                            ],
                        ],
                    ];


                    $event->sheet->getDelegate()->getStyle($cellRange4)->applyFromArray($styleArray4);
                } 
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

    public function headings(): array
    {
        return [
            'Item Name',
            'Bar Code',
            'Item Size',
            'Unit Cost',
            'Purchase',
            'Purchases Volume',
            'Purchases Cost'
        ];
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
        ];
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
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
        if (isset($this->form_date) && isset($this->to_date)) {

            $item_id = InvoiceDetails::whereDate('created_at', '>=', $this->form_date)
                ->whereDate('created_at', '<=', $this->to_date)
                ->select('item_id')->pluck('item_id')->toArray();

            $items = Item::whereIn('id',  $item_id)
                ->select('id')
                ->get();

            $classes = Item::whereIn('id',  $item_id)
                ->select('class_id')
                ->groupBy('class_id')
                ->get();

            $categories = Item::whereIn('id',  $item_id)
                ->select('category_id')
                ->groupBy('category_id')
                ->get();

            //dd(count($items));

            self::$count += count($classes) * count($categories) * count($items) * 3;

        } else {
            $items = [];
            $classes = [];
            $categories = [];
        }

        return view('admin.report_purchase_summary.exportexcel', [
            'items' => $items,
            'classes' => $classes,
            'categories' => $categories
        ]);
    }
}
