<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use App\Models\Gestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChartJSController extends Controller
{
    public function index(): View
    {
        
        $users = Gestion::select(DB::raw("COUNT(*) as count"), DB::raw("day(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(DB::raw("day(created_at)"), DB::raw("day(created_at)"))
                    ->pluck('count', 'month_name');
        
                    for ($i = 1; $i <= 12; $i++) {
                        $monthName = date('F', mktime(0, 0, 0, $i, 1));
                        $labels[] = $monthName;
                        $data[] = isset($users[$i]) ? $users[$i] : 0;
                    }
 
        $labels = $users->keys();
        $data = $users->values();
              
        return view('chart', compact('labels', 'data'));
    }

    
}
