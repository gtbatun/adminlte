<?php

namespace App\Exports;

use App\Models\Ticket;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithDrawings;



class TicketExport implements FromCollection , WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $fechaInicio;
    protected $fechaFin;
    // 
 

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        
    }
    
    public function styles(Worksheet $sheet){
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
               'size'  => 14,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
               'startColor' => ['argb' => '4285F4'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:I2' . $sheet->getHighestRow())->applyFromArray([
            'font' => [
                'size' => 12,
                'color' => ['rgb' => '000000'], // Color negro
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Color negro
                ],
            ],
        ]);

    }
   
    public function collection()
    {
        return Ticket::with('department')
        ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
        ->get()->map(function ($ticket) {
            return [
                'ID' => $ticket->id,
                'Nombre' => $ticket->title,
                'Usuario' => $ticket->usuario->name, // Accede al nombre del departamento
                'Area' => $ticket->area->name,
                'Departamento' => $ticket->department->name,
                'Categoria' => $ticket->category->name,
                'Estatus' => $ticket->status->name,
                'Fecha de Creación' => $ticket->created_at->format('Y-m-d'),
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ID',
            'Titulo',            
            'usuario',
            'Area',
            'Departamento',
            'Categoria',
            'Estatus',
            'Fecha de Creación',
        ];
    }
    
}
