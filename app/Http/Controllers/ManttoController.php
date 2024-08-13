<?php

namespace App\Http\Controllers;


use App\Models\Mantto;
use App\Models\Device;
use App\Models\Inventory;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Log;

class ManttoController extends Controller
{
    public function getTasksAndAssignByDevice($deviceIdTomantto)
    {
        // Obtener la lista de tareas por dispositivo
        $tasks = Mantto::where('device_id', $deviceIdTomantto)->get();

        // Obtener la lista de asignaciones por dispositivo
        $assignments = Inventory::where('device_id', $deviceIdTomantto)
                                ->where('tipo', 'entrega')
                                ->get();

        // Devolver ambas colecciones en un array asociativo
        return response()->json([
            'tasks' => $tasks,
            'assignments' => $assignments
        ]);
    }

    public function getTasksByDevice($deviceIdTomantto){
        // Obtener la lista de tareas por dispositivo
        $device_mantto = Mantto::where('device_id', $deviceIdTomantto)
        ->get();

        return response()->json($device_mantto);
    }
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
        'usermantto_id' => 'required'
        // 'user_id' => 'required'
        ]);

        // Log::info('Datos del request:', $request->all());

        // Crear un nuevo registro de mantenimiento
        $mantto = new Mantto();
        $mantto->statusdevice_id = $request->mantto_task_id;
        $mantto->device_id = $request->mantto_device_id;        
        $mantto->coment = $request->mantto_comment;
        $mantto->usermantto_id = $request->usermantto_id;
        $mantto->user_id = $request->user_id ? $request->user_id : null;
        $mantto->save();
        if ($request->mantto_status_id){
            // Actualizar el estado del dispositivo
            $device = Device::find($request->mantto_device_id);
            $device->statusdevice_id = $request->mantto_status_id;
            $device->save();
            // Actualizar el inventario del dispositivo
            // $inventory_device = Inventory::find($request->mantto_inventory_id);
            // $inventory_device->enable = 0;
            // $inventory_device->save();
        }

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
