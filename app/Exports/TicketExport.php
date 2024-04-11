<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TicketExport implements FromCollection
// class TicketExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Ticket::all();
    }
    // public function view():View
    //     {
    //         return view('exportTicket',[
    //             'ticket' => Ticket::all()
    //         ]);
    //     }
    
}
