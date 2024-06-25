<?php

namespace App\Http\Controllers;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Sucursal;
use App\Models\Devicedetail;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
      */
    //    'usuario','marca','tipodevice','statusdevice','sucursal','departamento'
    public function index()
    {
        $devices = Device::latest()->with('usuario','marca','tipodevice','statusdevice','sucursal','departamento')->get();
        return view('Device.index',['devices' => $devices]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipo_equipo = Devicedetail::where('type_device',1)->pluck('name','id');
        $marca = Devicedetail::where('type_device',2)->pluck('name','id');
        $almacenamiento = Devicedetail::where('type_device',3)->pluck('name','id');
        $procesador = Devicedetail::where('type_device',4)->pluck('name','id');
        $status = Devicedetail::where('type_device',5)->pluck('name','id');
        $equipo = new Device;
        $department = Department::pluck('name','id');
        $sucursal = Sucursal::pluck('name','id');
        return view('Device.create',compact('department','equipo','sucursal','tipo_equipo','marca','almacenamiento','procesador','status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request;
        Device::create($request->all());
        return redirect()->route('inventory.index')->with('success', 'Nuevo equipo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        return view('Device.edit',[
            'equipo' => $device,
            'tipo_equipo' => Devicedetail::where('type_device',1)->pluck('name','id'),
            'marca' => Devicedetail::where('type_device',2)->pluck('name','id'),
            'almacenamiento' => Devicedetail::where('type_device',3)->pluck('name','id'),
            'procesador' => Devicedetail::where('type_device',4)->pluck('name','id'),
            'status' => Devicedetail::where('type_device',5)->pluck('name','id'),
            'department' => Department::pluck('name','id'),
           'sucursal' => Sucursal::pluck('name','id')
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Device $device)
    {
        
        $device->update($request->all()); 
        return redirect()->route('device.index')->with('success','El equipoo fue actualizado con exito');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('inventory.index')->with('success', 'dispositivo Eliminado exitosamente');
    }
}
