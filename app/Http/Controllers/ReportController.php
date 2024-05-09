<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ticket;
// para la export de los reportes de tickets
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('Report.index');
    }

    public function generar(Request $request)    
    {
        // return $request;
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        //  $tickets = Ticket::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();
         $tickets = Ticket::whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), [$fechaInicio, $fechaFin])->get();

        // return $tickets;
        // return response()->json(compact('tickets'));
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        
        return view('Report.previsualizacion', compact('tickets','fechaInicio','fechaFin')); 
        
    }


    public function reportexport($fechaInicio, $fechaFin){       
        return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'Reporte de tickets_'.$fechaInicio.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        
    }

}
