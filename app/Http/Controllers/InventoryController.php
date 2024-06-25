<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Sucursal;
use App\Models\Device;
use App\Models\User;
class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('devices')->get();
        return view('Inventory.assignments',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $devices = Device::all();
        $users = User::pluck('name','id');
        return view('Inventory.index',['devices' => $devices, 'users' => $users]);
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
            'device_ids' => 'required|array',
            'device_ids.*' => 'exists:device,id',
        ]);
        // return $request;

        $user = User::findOrFail($request->user_id);
        $user->devices()->attach($request->device_ids);

        return redirect()->route('inventory.index')->with('success', 'Equipos asignados con éxito.');
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
