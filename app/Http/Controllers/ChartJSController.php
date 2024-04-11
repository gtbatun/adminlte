<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChartJSController extends Controller
{
    

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

    
}
