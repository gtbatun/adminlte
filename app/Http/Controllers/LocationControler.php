<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Area;
use App\Models\Category;

class LocationControler extends Controller
{
    public function getArea($department_id)
    {
        $area = Area::where('department_id', $department_id)->get();
        return response()->json($area);
    }

    public function getCategory($area_id)
    {
        $category = Category::where('area_id', $area_id)->get();
        return response()->json($category);
    }
}
