<?php

namespace App\Exports;

use App\GlobalConstants;
use App\Models\Batchmix;
use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Period;
use App\Models\Branch;
use App\Models\User;
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

class InventorySummaryExport implements FromView, WithHeadings, WithColumnWidths, WithStyles, WithDefaultStyles, WithCustomStartCell, WithEvents, WithDrawings, WithTitle
{

    public static $count = 0;

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

                $sheet->mergeCells('E1:I1');
                $sheet->setCellValue('E1', $user_name);

                $sheet->mergeCells('E2:I2');
                $sheet->setCellValue('E2', $file_date);

                $event->sheet->getDefaultRowDimension()->setRowHeight(30);  // All rows

                $styleArray = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];

                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);


                $cellRange1 = 'A2:I2'; // All headers

                $styleArray1 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange1)->applyFromArray($styleArray1);

                $cellRange2 = 'A3:I3'; // All headers

                $styleArray2 = [
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => Color::COLOR_WHITE],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle($cellRange2)->applyFromArray($styleArray2);

                $cellRange3 = 'A4:I4';

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
                    $cellRange4 = 'A' . $i + 5 . ':I' . $i + 5;
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
            'Invoice Date',
            'Vendor',
            'Invoice Number',
            'Liquor',
            'Wine',
            'Total Order',
            'Total Cost (excluding tax)',
            'Total Tax',
            'Total Cost (including tax)'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 40,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 30,
            'G' => 30,
            'H' => 20,
            'I' => 30
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

        $invoices = Invoice::where('period_id', $period_id)->where('branch_id', $branch_id)->get();
        if ($invoices->isEmpty()) {
            $invoices = [];
            $invoice_details = [];
            $class_names = [];
        } else {
            foreach ($invoices as $index => $invoice) {
                $invoice_details = InvoiceDetails::where('invoice_id', $invoice->id)
                    ->select('item_id', 'extended_price')
                    ->get();

                $class_names = [];
                foreach ($invoice_details as $index => $invoice_detail) {
                    if (array_key_exists($invoice_detail->item->class->name, $class_names)) {
                        $class_names[$invoice_detail->item->class->name] = $class_names[$invoice_detail->item->class->name] + $invoice_detail->extended_price;
                    } else {
                        $class_names[$invoice_detail->item->class->name] = $invoice_detail->extended_price;
                    }
                }
            }
        }

        self::$count += count($invoices);

        return view('admin.report_inventory_summary.exportexcel', [
            'invoices' => $invoices,
            'class_names' => $class_names
        ]);
    }
}
