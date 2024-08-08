<?php

namespace App\Http\Controllers;

use App\Models\Mantto;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Log;

class ManttoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        // Validar la solicitud
        $request->validate([
        'mantto_task_id' => 'required',
        'mantto_device_id' => 'required',        
        'mantto_comment' => 'required',
        'usermantto_id' => 'required',
        'user_id' => 'required'
        ]);

        Log::info('Datos del request:', $request->all());

        // Crear un nuevo registro de mantenimiento
        $mantto = new Mantto();
        $mantto->statusdevice_id = $request->mantto_task_id;
        $mantto->device_id = $request->mantto_device_id;        
        $mantto->coment = $request->mantto_comment;
        $mantto->usermantto_id = $request->usermantto_id;
        $mantto->user_id = $request->user_id;
        $mantto->save();

        return response()->json(['message' => 'Mantenimiento agregado con éxito']);

    }

    /**
     * Display the specified resource.
     */
    public function show(Mantto $mantto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mantto $mantto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mantto $mantto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mantto $mantto)
    {
        //
    }
}
