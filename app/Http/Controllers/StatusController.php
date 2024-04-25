<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = Status::latest()->paginate();
        return view('Status.index',['status' => $status]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $status = new Status;
        return view('Status.create',['status' => $status]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'            
        ]);

        Status::create($request->all());
        return redirect()->route('status.index')->with('success', 'Nuevo Estatus creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        return view('Ticket.index',[
            'status' => $status,
            'ticket' => $status->ticket()->with('status')->latest()->paginate(5)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        return view('Status.edit',['status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $status->update($request->all()); 
        return redirect()->route('status.index', $status)->with('success','El Estatus fue actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();
        return redirect()->route('status.index')->with('success', 'Estatus Eliminada exitosamente');
    }
}
