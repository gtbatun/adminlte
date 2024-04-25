<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $areas = Area::latest()->paginate();
        return view('area.index',['areas' => $areas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $area = new Area;
        return view('Area.create',['area' => $area]);
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

        Area::create($request->all());
        return redirect()->route('area.index')->with('success', 'Nuevo Ticket creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        return view('Ticket.index',[
            'area' => $area,
            'ticket' => $area->ticket()->with('area')->latest()->paginate()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('Area.edit',['area' => $area]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $area->update($request->all()); 
        return redirect()->route('area.index', $area)->with('success','El area fue actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('area.index')->with('success', 'Area Eliminada exitosamente');
    }
}
