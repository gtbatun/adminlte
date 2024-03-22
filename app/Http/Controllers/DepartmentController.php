<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::latest()->paginate(5);
        return view('department.index',['departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $department = new Department;
        return view('department.create',['department' => $department]);
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

        Department::create($request->all());
        return redirect()->route('department.index')->with('success', 'Nuevo Departamento creado exitosamente');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return view('ticket.index',[
            'department' => $department,
            'ticket' => $department->ticket()->with('department')->latest()->paginate()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('department.edit',['department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $department->update($request->all()); 
        return redirect()->route('department.index', $department)->with('success','El Departamento fue actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('department.index')->with('success', 'Departamento Eliminado exitosamente');
    }
}
