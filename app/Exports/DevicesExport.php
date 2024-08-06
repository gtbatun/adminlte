<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Illuminate\Support\Facades\DB;

class DevicesExport implements FromCollection , WithHeadings, WithStyles
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

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Descripcion',
            'Estado',                        
            'Asignado a',
        ];
    }
    
    public function collection()
    {
        $device = DB::table('device')->select(['device.id','device.name','device.description','users.name as user_name','devicedetail.name as status_device'])
        ->leftjoin('users','users.id','=', 'device.user_id')
        ->join('devicedetail','devicedetail.id','=', 'device.statusdevice_id')
        ->whereBetween('device.created_at', [$this->fechaInicio, $this->fechaFin])
        ->get();
        return $device;
    }
}

