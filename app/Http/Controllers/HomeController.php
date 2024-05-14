<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use App\Models\Gestion;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index():View
    {
         

// Obtener el recuento de gestiones por Agente
$user = Auth::user();
if($user->is_admin == 10){
// filtar la infoirmacion que pueda visualizar el emplea<do en el dashboard
$agente = Ticket::whereYear('ticket.created_at', date('Y'))
->where('gestion.status_id', '=', '4')
->where('users.is_admin', '=', '10')
->join('gestion', 'gestion.ticket_id', '=', 'ticket.id')
->join('users', 'users.id', '=', 'gestion.user_id')
->selectRaw('COUNT(*) as count, users.name as user_name')
->groupBy('users.name')
->pluck('count', 'user_name');
}else{
    echo "hola";
    $agente = Ticket::whereMonth('ticket.created_at', date('m'))
                ->where('ticket.department_id', '=', auth()->user()->department_id)
            // ->where('gestion.status_id', '=', '4')
            // ->where('users.is_admin', '=', '10')
            // ->join('gestion', 'gestion.ticket_id', '=', 'ticket.id')
            ->join('users', 'users.id', '=', 'ticket.user_id')
            ->selectRaw('COUNT(*) as count, users.name as user_name')
            ->groupBy('user_id')
            ->pluck('count', 'user_name');
}

            

        // Convertir los datos en un array para ser utilizados en la gráfica

        $a_labels = $agente->keys();
        $a_data = $agente->values();

// Obtener el recuento de Tickets por Departamento
        if($user->is_admin == 10){
                $department = Ticket::whereYear('ticket.created_at', date('Y'))                
                ->whereMonth('ticket.created_at', '=', date('m'))
                ->join('department', 'ticket.department_id', '=', 'department.id')
                ->selectRaw('COUNT(*) as count, department.name as department_name')
                ->groupBy('department.name')
                ->pluck('count', 'department_name');
        }else{
            $department = Ticket::whereYear('ticket.created_at', date('Y'))
                ->whereMonth('ticket.created_at', '=', date('m'))                
                ->where('ticket.department_id', '=', auth()->user()->department_id)
                ->join('area', 'ticket.area_id', '=', 'area.id')
                ->selectRaw('COUNT(*) as count, area.name as area_name')
                ->groupBy('area.name')
                ->pluck('count', 'area_name');
        }
        // Convertir los datos en un array para ser utilizados en la gráfica

        $d_labels = $department->keys();
        $d_data = $department->values();

// Obtener el recuento de tickets por día para el año actual
        if($user->is_admin == 10){
                $t_dia1 = Ticket::whereYear('created_at', date('Y'))
                ->whereMonth('ticket.created_at', '=', date('m'))
                ->selectRaw('COUNT(*) as count, DAY(created_at) as day')
                ->groupBy('day')
                ->pluck('count', 'day');
        }else{
            $t_dia1 = Ticket::whereYear('created_at', date('Y'))
                ->whereMonth('ticket.created_at', '=', date('m'))
                ->where('ticket.department_id', '=', auth()->user()->department_id)
                ->selectRaw('COUNT(*) as count, DAY(created_at) as day')
                ->groupBy('day')
                ->pluck('count', 'day');

        }

        // Llenar los datos para todos los días del mes actual
        for ($i = 1; $i <= 31; $i++) {
        $labels1[] = $i;
        $data1[] = isset($t_dia1[$i]) ? $t_dia1[$i] : 0;
        }

        // Convertir los datos en un array para ser utilizados en la gráfica
        $labels1 = array_values($labels1);
        $data1 = array_values($data1);
        //


        /// recien agregado para poner datos en el dashboard
        if($user->is_admin == 10){
        $ticketCounts = DB::table('ticket')
            ->select('status_id', DB::raw('COUNT(*) as total'),'status.name')
            ->whereMonth('ticket.created_at', '=', date('m'))
            ->whereYear('ticket.created_at', '=', date('Y'))
            ->join('status','ticket.status_id', '=', 'status.id')
            ->groupBy('status_id')
            ->get();
        }else{
           
            $ticketCounts = DB::table('ticket')
            ->select('status_id', DB::raw('COUNT(*) as total'), 'status.name')
            ->join('status', 'ticket.status_id', '=', 'status.id')
            ->whereMonth('ticket.created_at', '=', date('m'))
            ->whereYear('ticket.created_at', '=', date('Y'))
            ->where('ticket.department_id', '=', auth()->user()->department_id)
            ->groupBy('status_id', 'status.name')
            ->get();
        }
       

   
        // return view('home', compact('a_labels', 'a_data','d_labels', 'd_data','labels1', 'data1'));
        return view('chart', compact('a_labels', 'a_data','d_labels', 'd_data','labels1', 'data1','ticketCounts'));
        // return view('home');
    }
}
