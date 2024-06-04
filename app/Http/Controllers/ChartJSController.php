<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\Auth;
class ChartJSController extends Controller
{
    public function index(){
        return view('Report.generar');
    }

    public function store(Request $request){
        return $request;
        return "desde store";
    }
    

    public function ticketsChart()
    {
        return view('graf');
    }

    public function ticketsData(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $ticketData = Ticket::whereBetween('created_at', [$fromDate, $toDate])
            ->groupBy('date')
            ->selectRaw('date(created_at) as date, count(*) as count')
            ->get();

        $data = [];
        foreach ($ticketData as $ticket) {
            $data[] = $ticket->count;
        }

        return response()->json(['data' => $data]);
    }
    /** ------------------------------------------------------------------------------------------------------------ */

    /*** -----------------------------------------------------------------------------------------------------------  */
    
    public function getDataMonth(Request $request)
    {
        $month = $request->input('month');
        if (!$month) {
            return response()->json(['error' => 'Month is required'], 400);
        }

        $data = $this->getDataMonth1($month);        
        return response()->json($data);
    } 
    
    private function getDataMonth1($startDate)
    {
        $user = Auth::user();

        // Datos de prueba
        $agente = collect([
            'User1' => 10,
            'User2' => 15,
            'User3' => 5
        ]);

        $a_labels = $agente->keys()->toArray();
        $a_data = $agente->values()->toArray();

        return [
            'labels' => $a_labels,
            'data' => $a_data
        ];
    }
    private function getDataMonth12($startDate)
    {   
           
    $user = Auth::user();
    $agente = collect();   

    if($user->is_admin == 10){
    $agente = Ticket::where('ticket.created_at','>=', $startDate)
    ->where('gestion.status_id', '=', '4')
    ->where('users.is_admin', '=', '10')
    ->join('gestion', 'gestion.ticket_id', '=', 'ticket.id')
    ->join('users', 'users.id', '=', 'gestion.user_id')
    ->selectRaw('COUNT(*) as count, users.name as user_name')
    ->groupBy('users.name')
    ->pluck('count', 'user_name');
    }else{
    $agente = Ticket::where('ticket.created_at','>=', $startDate)
    ->where('ticket.department_id', '=', auth()->user()->department_id)
    ->join('users', 'users.id', '=', 'ticket.user_id')
    ->selectRaw('COUNT(*) as count, users.name as user_name')
    ->groupBy('user_id')
    ->pluck('count', 'user_name');
    }
    // Convertir los datos en un array para ser utilizados en la gráfica
        $a_labels = $agente->keys()->toArray();
        $a_data = $agente->values()->toArray();

        return [
            'labels' => $a_labels,
            'data' => $a_data
        ];
        
    }
    /** ------------------------------------------------------------------------------------------------- */

    /** ------------------------------------------------------------------------------------------------- */
    public function getChartData(Request $request)
    {
        $range = $request->input('range', 'day');     
        $data = $this->getDataByRange($range);        
        return response()->json($data);
    }    
    private function getDataByRange($range)
    {
        $now = Carbon::now();
        $startDate = null;

        switch ($range) {
            case 'day':
                $startDate = $now->startOfDay();
                break;
            case 'week':
                $startDate = $now->startOfWeek();
                break;
            case 'month':
                $startDate = $now->startOfMonth();
                break;
            case 'year':
                $startDate = $now->startOfYear();
                break;
        }
        // Aquí puedes llamar a métodos específicos para obtener los datos
        $data = $this->getDataFromDate($startDate, $range);
        return $data;
    }
    
    private function getDataFromDate($startDate, $range)
    {       
    $user = Auth::user();
    $agente = collect();   

    if($user->is_admin == 10){
    $agente = Ticket::where('ticket.created_at','>=', $startDate)
    ->where('gestion.status_id', '=', '4')
    ->where('users.is_admin', '=', '10')
    ->join('gestion', 'gestion.ticket_id', '=', 'ticket.id')
    ->join('users', 'users.id', '=', 'gestion.user_id')
    ->selectRaw('COUNT(*) as count, users.name as user_name')
    ->groupBy('users.name')
    ->pluck('count', 'user_name');
    }else{
    $agente = Ticket::where('ticket.created_at','>=', $startDate)
    ->where('ticket.department_id', '=', auth()->user()->department_id)
    ->join('users', 'users.id', '=', 'ticket.user_id')
    ->selectRaw('COUNT(*) as count, users.name as user_name')
    ->groupBy('user_id')
    ->pluck('count', 'user_name');
    }
    // Convertir los datos en un array para ser utilizados en la gráfica
        $a_labels = $agente->keys()->toArray();
        $a_data = $agente->values()->toArray();

        return [
            'labels' => $a_labels,
            'data' => $a_data
        ];
        
    }
/**--------------------------------------------------------------------------------------- */
    public function getDatadepartment(Request $request)
    {
        $range = $request->input('range', 'day');     
        $data = $this->getDataByRange1($range);        
        return response()->json($data);
    }    
    private function getDataByRange1($range)
    {
        $now = Carbon::now();
        $startDate = null;

        switch ($range) {
            case 'day':
                $startDate = $now->startOfDay();
                break;
            case 'week':
                $startDate = $now->startOfWeek();
                break;
            case 'month':
                $startDate = $now->startOfMonth();
                break;
            case 'year':
                $startDate = $now->startOfYear();
                break;
        }
        // Aquí puedes llamar a métodos específicos para obtener los datos
        $data = $this->getDataFromDate1($startDate, $range);
        return $data;
    }
    
    private function getDataFromDate1($startDate, $range)
    {       
            $user = Auth::user();
            $agente = collect();   
            // Obtener el recuento de Tickets por Departamento
            if($user->is_admin == 10){
                $department = Ticket::where('ticket.created_at','>=', $startDate)                
                ->whereMonth('ticket.created_at', '=', date('m'))
                ->join('department', 'ticket.department_id', '=', 'department.id')
                ->selectRaw('COUNT(*) as count, department.name as department_name')
                ->groupBy('department.name')
                ->pluck('count', 'department_name');
        }else{
            $department = Ticket::where('ticket.created_at','>=', $startDate)
                ->whereMonth('ticket.created_at', '=', date('m'))                
                ->where('ticket.department_id', '=', auth()->user()->department_id)
                ->join('area', 'ticket.area_id', '=', 'area.id')
                ->selectRaw('COUNT(*) as count, area.name as area_name')
                ->groupBy('area.name')
                ->pluck('count', 'area_name');
        }
        // Convertir los datos en un array para ser utilizados en la gráfica
        $d_labels = $department->keys()->toArray();
        $d_data = $department->values()->toArray();

        return [
            'labels' => $d_labels,
            'data' => $d_data
        ];        
    }
/** -----------------------------------------------------------------------------------------------  */
public function getDataDay(Request $request)
{
    $range = $request->input('range', 'day');     
    $data = $this->getDataByRangeDay($range);        
    return response()->json($data);
}    
private function getDataByRangeDay($range)
{
    $now = Carbon::now();
    $startDate = null;

    switch ($range) {
        case 'day':
            $startDate = $now->startOfDay();
            break;
        case 'week':
            $startDate = $now->startOfWeek();
            break;
        case 'month':
            $startDate = $now->startOfMonth();
            break;
        case 'year':
            $startDate = $now->startOfYear();
            break;
    }
    // Aquí puedes llamar a métodos específicos para obtener los datos
    $data = $this->getDataFromDay($startDate, $range);
    return $data;
}

private function getDataFromDay($startDate, $range)
{       
        $user = Auth::user();
        $day = collect();   
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
        

    return [
        'labels' => $labels1,
        'data' => $data1
    ];        
}

    
}
