<?php

namespace App\Exports;

use App\Models\Ticket;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketExport implements FromCollection , WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ticket::with('department')->get()->map(function ($ticket) {
            return [
                'ID' => $ticket->id,
                'Nombre' => $ticket->title,
                // 'Email' => $ticket->email,
                'Usuario' => $ticket->usuario->name, // Accede al nombre del departamento
                'Area' => $ticket->area->name,
                'Departamento' => $ticket->department->name,
                // 'Fecha de Creación' => $ticket->created_at,
                // Agrega más campos si es necesario
                'Encargado' => $ticket->department->name,
            ];
        });
    }
    public function headings(): array
    {
        return [
            'ID',
            'Titulo',
            // 'Descripcion',
            // 'Fecha finalizacion',
            // 'Imagen',
            // 'Creacion',
            // 'Actualizacion',
            'usuario',
            'Area',
            'Departamento',
            'Encargado',
            // 'Categoria',
        ];
    }
    
}
