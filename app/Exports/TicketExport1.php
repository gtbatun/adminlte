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
    

    public function styles(Worksheet $sheet)
    {
        $highestColumn = $sheet->getHighestColumn();
        $highestRow = $sheet->getHighestRow();
    
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
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
    
        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
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


$ticket = DB::table('ticket')
            ->select([
                'ticket.id',
                'department_creador.name AS nombre_dep_creador',
                'department_asignado.name AS nombre_dep_asignado',
                'area.name as concepto',
                'category.name as categoria',
                'ticket.title',
                DB::raw('DATE(ticket.created_at) AS fecha'),
                'status.name AS estado',
                DB::raw("
                    CASE 
                        WHEN status.id <> 4 THEN (
                            SELECT users.name 
                            FROM gestion 
                            INNER JOIN users AS users ON users.id = gestion.user_id
                            INNER JOIN department ON users.department_id = department.id
                            WHERE department.id IN (20, 21) AND gestion.ticket_id = ticket.id
                            ORDER BY gestion.id DESC
                            LIMIT 1
                        )
                        ELSE users.name 
                    END AS personal_sistemas
                ")
            ])
            ->join('department as department_creador','department_creador.id', '=', 'ticket.type')
            ->join('department as department_asignado','department_asignado.id', '=', 'ticket.department_id')
            ->join('area', 'area.id', '=', 'ticket.area_id')
            ->join('category', 'category.id', '=', 'ticket.category_id')
            ->join('status', 'status.id', '=', 'ticket.status_id')
            ->leftJoin(DB::raw('(SELECT gestion.ticket_id, MAX(gestion.id) AS max_gestion_id
                                FROM gestion
                                GROUP BY gestion.ticket_id) AS max_gestion'), 
                        'ticket.id', '=', 'max_gestion.ticket_id')
            ->leftJoin('gestion', 'gestion.id', '=', 'max_gestion.max_gestion_id')
            ->leftJoin('users', 'users.id', '=', 'gestion.user_id')
            ->whereBetween('ticket.created_at', [$this->fechaInicio, $this->fechaFin])
            ->get();