<?php

namespace App\Http\Controllers;


use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Sucursal;
use App\Models\Device;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
class InventoryController extends Controller
{
    public function unassignDevicesMassive(Request $request){
        $deviceIds = $request->input('deviceIds');
        $newUserId = $request->input('newUserId');
        if (is_array($deviceIds) && count($deviceIds) > 0) {

             // Lógica para reasignar los dispositivos
            foreach ($deviceIds as $deviceId) {
            // Asigna el dispositivo al nuevo usuario y realiza otras operaciones necesarias
            // Ejemplo: 
            $device = Device::find($deviceId);
                if ($device) {
                    $device->user_id = $newUserId;
                    $device->save();
                }
            }
            return response()->json(['success' => true]);
        }  
        return response()->json(['error' => 'No se encontraron dispositivos seleccionados.'], 400); 

    }
    public function getAssignByDevice($deviceIdToMantto){
        $device_mantto = Inventory::where('device_id', $deviceIdToMantto)
        ->where('tipo','entrega')->get();
        return response()->json($device_mantto);
    }
    
    public function deleteDevice(Request $request, $deviceId)
    {
        $comment = $request->input('comment');
        $staff_id = $request->staff_id;
        $statusId = $request->input('status_id');

        $device = Device::find($deviceId);
        $device->statusdevice_id = $statusId; // Cambiar a un estado que represente la eliminación lógica
        $device->user_id = null;
        $device->save();

        $inventory_device = Inventory::find($request->inventory_id);
        $inventory_device->enable = 0;
        $inventory_device->save();

        // Registrar la eliminación en la tabla inventario
        Inventory::create([
            'device_id' => $deviceId,
            'user_id' => $staff_id,
            'coment' => $comment,
            'tipo' => 'devolucion',
            'enable' => 0,
        ]);

        return response()->json(['success' => 'Dispositivo eliminado correctamente.']);
    }
    public function assignDevices(Request $request)
    {
        $userId = $request->input('user_id');
        $devices = $request->input('devices');

        $userData = User::find($userId);

        foreach ($devices as $deviceData) {
            $deviceId = $deviceData['deviceId'];
            $coment = $deviceData['coment'];

            $device = Device::find($deviceId);
            $device->user_id = $userId;
            $device->department_id = $userData->department_id;
            $device->statusdevice_id = 13;
            $device->save();
            // Registrar la asignación en la tabla inventario
            Inventory::create([
                'device_id' => $deviceId,
                'user_id' => $userId,
                'enable' => 1,
                'tipo' => 'entrega',
                'coment' => $coment, // Guardar el comentario
            ]);
        }
        return response()->json(['success' => 'Dispositivos asignados correctamente.']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $devices = Inventory::where('enable',1)->get();
        // $users = User::with('devices')->get();
        $devices = DB::table('device_user')
            ->join('device', 'device_user.device_id', '=', 'device.id')
            ->join('users', 'device_user.user_id', '=', 'users.id')
            ->where('device_user.enable', 1)
            ->select('device_user.id', 'device.name as device_name', 'users.name as user_name')
            ->get();   
        
        $usersWithDevices = DB::table('device_user')
            ->join('device', 'device_user.device_id', '=', 'device.id')
            ->join('users', 'device_user.user_id', '=', 'users.id')
            ->where('device_user.enable', 1)
            ->select('device_user.id', 'device.name as device_name', 'users.name as user_name', 'users.id as user_id')
            ->orderBy('users.id')
            ->get()
            ->groupBy('user_id');

        return view('Inventory.index',compact('usersWithDevices'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::all();
        $users = User::pluck('name','id');
        return view('Inventory.assignments',['devices' => $devices, 'users' => $users]);
    }
    /**
     * Fucnion para la asignacion de equipos de computo a los usuarios
     */
    public function assignments()
    {
        $users = User::with('devices')->get();
        return view('Inventory.assignments',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'device_id' => 'required',
            'coment' => 'nullable',
        ]);
        // Guardar los datos en la tabla device_user
        $inventory = new Inventory();
        $inventory->device_id = $request->device_id;
        $inventory->user_id = $request->user_id;
        $inventory->coment = $request->coment;
        $inventory->tipo = 'entrega';
        $inventory->enable = 1;
        $inventory->save();
        /** actualizar o gregar el usuario a la seccion del device */
        if(isset($request->user_id)){
            $user = User::find($request->user_id);
            $device = Device::find($request->device_id);
            $device->user_id = $request->user_id;
            $device->department_id = $user->department_id;            
            $device->statusdevice_id = 13;
            $device->update();
        }
        return redirect()->back()->with('success', 'El dispositivo ha sido asignado al usuario correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $device = Device::findOrFail($id);
        $device->user_id = null;
        // $device->save();
        return $inventory;

        return redirect()->route('inventory.index')->with('success', 'Equipo desasignado con éxito.');
    }
}
