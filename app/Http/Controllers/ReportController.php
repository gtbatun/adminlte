<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Devicedetail;

use App\Models\Department;
// para la export de los reportes de tickets
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;
use Exception;
use Log;
use App\Exports\DevicesExport;


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
        //  $tickets4 = Ticket::whereBetween('created_at', [$fechaInicio, $fechaFin])->get();
        $tickets = Ticket::whereBetween(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"),[$fechaInicio, $fechaFin])
        ->get();  
        // return $tickets;
        // return response()->json(compact('tickets'));
        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        // return $tickets;
        return view('Report.previsualizacion', compact('tickets','fechaInicio','fechaFin')); 
    }


   
    public function reportexport1($fechaInicio, $fechaFin){       
        return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'Reporte de tickets_'.$fechaInicio.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);        
    }

    public function reportexcel($start_date, $end_date)
    {
        $fechaInicio = $start_date;
        $fechaFin = $end_date;
        return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'Reporte de tickets_'.$fechaInicio.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // if (empty($fechaInicio) || empty($fechaFin)) {
        //     return response()->json(['error' => 'Fechas no proporcionadas'], 400);
        // }
        // return response()->json(['start_date' => $fechaInicio, 'end_date' => $fechaFin]);
    
    }
    public function reportexcel_device($start_date, $end_date)
    {
        $fechaInicio = $start_date;
        $fechaFin = $end_date;
        return Excel::download(new DevicesExport($fechaInicio, $fechaFin), 'Reporte de equipos_'.$fechaInicio.'.xlsx', \Maatwebsite\Excel\Excel::XLSX);
            
    }

    /** secccion para visualizar los reportes los datos en la tabla */
    public function search(Request $request)
    {
        $reporttype = $request->input('reporttype');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        if ($reporttype === 'tickets') {
        $data = DB::table('ticket')
            ->select([
                'ticket.id',
                'sucursal.name as user_sucursal',
                'department_creador.name AS creador',
                'department_asignado.name as asignado',
                'area.name as concepto',
                'category.name as categoria',
                'ticket.title',
                DB::raw('DATE(ticket.created_at) AS fecha'),
                'status.name AS estado',
                DB::raw("
                    CASE 
                        WHEN status.id <> 4 THEN (
                            SELECT gestion_users.name 
                            FROM gestion 
                            INNER JOIN users AS gestion_users ON gestion_users.id = gestion.user_id
                            inner JOIN department ON gestion_users.department_id = department.id                            
                            WHERE department.id = ticket.department_id and gestion.ticket_id = ticket.id
                            ORDER BY gestion.id DESC
                            LIMIT 1
                        )
                        ELSE gestion_users.name 
                    END AS personal_sistemas
                ")
            ])
            ->join('users AS creator_users', 'creator_users.id', '=', 'ticket.user_id')
            ->join('sucursal', 'sucursal.id', '=', 'creator_users.sucursal_id')
            ->join('department as department_creador', 'department_creador.id', '=', 'ticket.type')
            ->join('department as department_asignado', 'department_asignado.id', '=', 'ticket.department_id')
            ->join('area', 'area.id', '=', 'ticket.area_id')
            ->join('category', 'category.id', '=', 'ticket.category_id')
            ->join('status', 'status.id', '=', 'ticket.status_id')
            ->leftJoin(DB::raw('(SELECT gestion.ticket_id, MAX(gestion.id) AS max_gestion_id
                                FROM gestion
                                GROUP BY gestion.ticket_id) AS max_gestion'), 
                        'ticket.id', '=', 'max_gestion.ticket_id')
            ->leftJoin('gestion', 'gestion.id', '=', 'max_gestion.max_gestion_id')
            ->leftJoin('users AS gestion_users', 'gestion_users.id', '=', 'gestion.user_id')
            ->whereBetween('ticket.created_at', [$startDate, $endDate])
            ->get();

        } elseif ($reporttype === 'equipos') {
            $data = DB::table('device')->select(['device.id','device.name','device.description','users.name as user_name','devicedetail.name as status_device'])
            ->leftjoin('users','users.id','=', 'device.user_id')
            ->join('devicedetail','devicedetail.id','=', 'device.statusdevice_id')
            ->whereBetween('device.created_at', [$startDate, $endDate])
            ->get();

        }else {
            $data = collect();
        }        

        return response()->json(['data' => $data]);
    }

    /**nueva seccion para una nueva consulta */
    public function search2(Request $request)
    {
        try {        
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $tickets = Ticket::with(['creatorDepartment', 'assignedDepartment', 'area', 'category', 'status', 'usuario'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // Procesar cada ticket para determinar `personal_sistemas`
        $tickets->each(function ($ticket) {
            if ($ticket->status_id != 4) {
                $gestion = $ticket->gestions()
                    ->join('users', 'users.id', '=', 'gestion.user_id')
                    ->join('department', 'users.department_id', '=', 'department.id')
                    ->whereIn('department.id', [20, 21])
                    ->orderBy('gestion.id', 'desc')
                    ->first();
                $ticket->personal_sistemas = $gestion ? $gestion->name : null;
            } else {
                $ticket->personal_sistemas = $ticket->usuario ? $ticket->usuario->name : null;
            }
            
        });

        return response()->json(['data' => $tickets]);
    } catch (Exception $e) {
        Log::error('Error fetching tickets: ' . $e->getMessage());
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
        
    }

}
