<?php


namespace App\Exports;

use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TestExport implements FromArray, WithHeadings, WithMapping, Responsable, WithEvents
{
    use Exportable;

    protected $title;
    protected $fileName;
    protected $data;

    public function __construct($data)
    {
        $this->title = 'test';
        $this->fileName = $this->title . '.xlsx';
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return ['col1', ' ', 'col2', 'col3', 'col4', 'col5', 'col6', 'col7', 'col8'];
    }

    public function map($row): array
    {
        return [
            $row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $spreadsheet = $event->getWriter()->getDelegate();
                $spreadsheet->getDefaultStyle()->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
                $activeSheet = $spreadsheet->getActiveSheet();
                $activeSheet->mergeCells('A1:B1');
            }
        ];
    }
}