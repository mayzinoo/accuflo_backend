<?php

namespace App\Exports;

use App\GlobalConstants;
use App\Models\Recipe;
use App\Models\Period;
use App\Models\PriceLevel;
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

class SalesExport implements FromView, WithHeadings, WithColumnWidths, WithStyles, WithDefaultStyles, WithCustomStartCell, WithEvents, WithDrawings, WithTitle
{

    protected $station_id;
    public static $count = 0;

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

                //$user_name = $user->name;
                $file_date = $new_start_date[0] . ' ' . $new_start_date[1] . ' to ' . $new_end_date[0] . ' ' . $new_end_date[1] . ' ' . $new_end_date[2];

                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('E1:F1');
                $sheet->setCellValue('E1', $user_name);

                $sheet->mergeCells('E2:F2');
                $sheet->setCellValue('E2', $file_date);

                $event->sheet->getDefaultRowDimension()->setRowHeight(30);  // All rows

                $styleArray = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];

                $cellRange = 'A1:F1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);


                $cellRange1 = 'A2:F2'; // All headers

                $styleArray1 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray1);

                $cellRange2 = 'A3:F3'; // All headers

                $styleArray2 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray2);

                $cellRange3 = 'A4:F4';

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
                    $cellRange4 = 'A' . $i + 5 . ':F' . $i + 5;
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
            'PLU',
            'Item Name',
            'Cost',
            'Tax / Discount',
            'Regular',
            'PC',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 40,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20
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
        $branch_id = session()->get('branch_id');
        $period_id = session()->get('period_id');
       
        $recipes = Recipe::where([['branch_id', $branch_id], ['period_id', $period_id], ['station_id', $this->station_id]])
            ->get();

        $price_levels = PriceLevel::where([['station_id', $this->station_id], ['client_id', $branch_id]])->get();

        self::$count += count($price_levels) + count($recipes);

        return view('admin.report_sale.exportexcel', [
            'recipes' => $recipes
        ]);
    }
}
