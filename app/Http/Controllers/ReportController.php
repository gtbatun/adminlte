<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ticket;
// para la export de los reportes de tickets
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;

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

        // if ($request->accion == 'exportar') {
        //     return redirect()->route('export', ['fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]);
        // }
    
        $tickets = Ticket::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();

        // return $tickets;
        // return response()->json(compact('tickets'));
        
        return view('report.previsualizacion', compact('tickets'));
    
        
    }


    public function export10(Request $request){
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        // public function export(){
        // return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'Tickets.xlsx');        
        return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'tickets.xlsx');
        // return Excel::download(new TicketExport('2024-04-15', '2024-04-19'), 'Tickets.xlsx');
        
    }

}
