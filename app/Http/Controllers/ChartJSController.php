<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
    
    
    /** */

    public function getData(Request $request)
    {
        $startDate = now()->startOfWeek()->format('Y-m-d');
        $endDate = now()->endOfWeek()->format('Y-m-d');
        // $data = Ticket::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $data2 = Ticket::whereBetween('created_at', [$startDate, $endDate])
        ->selectRaw('COUNT(*) AS ticket_suma,user_id')->groupby('user_id')
        ->get();
        $data = DB::table('ticket')
            ->select('user_id', DB::raw('COUNT(*) as ticket_count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function getMoreData(Request $request)
    {
        // Obtener más datos según los parámetros enviados por el cliente
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = Ticket::whereBetween('created_at', [$startDate, $endDate])->get();

        return response()->json(['data' => $data]);
    }

    
}
