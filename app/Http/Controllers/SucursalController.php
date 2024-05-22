<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucursal = Sucursal::latest()->paginate();
        return view('Sucursal.index',['sucursal' => $sucursal]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sucursal = new Sucursal;
        return view('Sucursal.create',['sucursal' => $sucursal]);
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

        Sucursal::create($request->all());
        return redirect()->route('sucursal.index')->with('success', 'Nueva sucursal creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal)
    {
        return view('Sucursal.edit',['sucursal' => $sucursal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sucursal $sucursal)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $sucursal->update($request->all()); 
        return redirect()->route('sucursal.index', $sucursal)->with('success','La sucursal fue actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sucursal $id)
    {
        //
    }
}
