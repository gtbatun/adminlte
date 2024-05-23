<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Area;
use App\Models\Category;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**solicitudes para las departamento dividir areas y categorias(select option anidado) */
    public function getArea($department_id)
    {
        $areas = Area::where('department_id', $department_id)->get();
        return response()->json($areas);
    }

    public function getCategory($area_id)
    {
        $categorias = Category::where('area_id', $area_id)->get();
        
        return response()->json($categorias);
    }

    /** */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::latest()->paginate();
        return view('Department.index',['departments' => $departments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $department = new Department;
        return view('Department.create',[
            'sucursal' => Sucursal::pluck('name','id')
            ,'department' => $department]);
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
        return view('Ticket.index',[
            'department' => $department,
            'ticket' => $department->ticket()->with('department')->latest()->paginate()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('Department.edit',[
            'sucursal' => Sucursal::pluck('name','id'),
            'department' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'sucursal_id' => 'required'
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
