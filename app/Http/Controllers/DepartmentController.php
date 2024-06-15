<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Area;
use App\Models\Category;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{    
    public function getDepartments()
    {
        $sucursalId = auth()->user()->sucursal_id;

        $departments = Department::where(function($query) use ($sucursalId) {
            $query->whereJsonContains('sucursal_ids', (string) $sucursalId);
        })
        ->where('enableforticket', 1)
        ->get();
        if ($departments->isEmpty()) {
            // Depuración adicional
            dd('No se encontraron departamentos', Department::all());
        }   
                          
       return response()->json($departments);
    }
    /**solicitudes para las departamento dividir areas y categorias(select option anidado) */
    public function getArea($department_id)
    {
        $areas = Area::where('department_id', $department_id)->get();
        return response()->json($areas);
        // return response()->json(['data' => $areas]);
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
        $departments = Department::all();

        // Recuperar todos los IDs de sucursales únicos
        $sucursalIds = [];
        foreach ($departments as $department) {
            $sucursalIds = array_merge($sucursalIds, json_decode($department->sucursal_ids, true));
        }
        $sucursalIds = array_unique($sucursalIds);

        // Recuperar todas las sucursales necesarias en una sola consulta
        $sucursales = Sucursal::whereIn('id', $sucursalIds)->pluck('name', 'id')->toArray();

        // Añadir los nombres de las sucursales a cada departamento
        foreach ($departments as $department) {
            $departmentSucursalIds = json_decode($department->sucursal_ids, true);
            $departmentSucursales = [];
            foreach ($departmentSucursalIds as $id) {
                if (isset($sucursales[$id])) {
                    $departmentSucursales[] = $sucursales[$id];
                }
            }
            $department->sucursal_names = $departmentSucursales;
        }

        return view('Department.index', ['departments' => $departments]);
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'sucursal_ids' => 'required|array',
            'sucursal_ids.*' => 'exists:sucursal,id',
            'enableforticket' => 'required|boolean',

        ]);
        // return $request;
        Department::create([          
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'sucursal_ids' => json_encode($request->input('sucursal_ids')),
            'enableforticket' => $request->input('enableforticket'),
        ]);
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
            'sucursal_ids' => 'required'
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
