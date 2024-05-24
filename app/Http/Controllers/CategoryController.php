<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::with('areas')->latest()->paginate();
        $categories = Category::with('area')->get();
        return view('Category.index',['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category = new Category;
        return view('Category.create',['areas' => Area::pluck('name','id'),'category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'area_id' => 'required'            
        ]);

        Category::create($request->all());
        return redirect()->route('category.index')->with('success', 'Nueva categoria creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('Ticket.index',[
            'category' => $category,
            'ticket' => $category->ticket()->with('category')->latest()->paginate()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('Category.edit',['areas' => Area::pluck('name','id'),'category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'area_id' => 'required'
        ]);
        $category->update($request->all()); 
        return redirect()->route('category.index', $category)->with('success','La categoria fue actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Categoria Eliminada exitosamente');
    }
}
