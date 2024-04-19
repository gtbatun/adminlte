<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ticket;

class ReportController extends Controller
{
    public function index()
    {
        return view('Report.index');
    }

    

    public function generar(Request $request)
    {
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
    
        $tickets = Ticket::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();

        // return $tickets;
        // return response()->json(compact('tickets'));
        
        return view('report.previsualizacion', compact('tickets'));
         // Renderiza la vista y devuelve el HTML como una respuesta AJAX
        // return response()->json([
        // 'html' => view('Report.previsualizacion', compact('tickets'))->render()
        // ]);
        // return response()->json(['message' => 'Ticket created successfully'], compact('tickets'));
    
        
    }
}
