<?php

namespace App\Http\Controllers;

use App\Models\Mantto;
use Illuminate\Http\Request;

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
        // dd($request->all());

        return response()->json(['message' => 'Success']);
       
        $userId = $request->input('user_id');
        $device_id = $request->input('device_id');

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
