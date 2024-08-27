<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Models\Ticket; //se agrega para poder actualizar el status del ticket
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Notifications\GestionNotification;
use App\Models\Department;
use Illuminate\Support\Facades\Log;

class GestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getGestiones(){
        // $gestiones = Gestion::where('ticket_id',$ticket->id )->with('usuario')->orderBy('created_at', 'desc')->get();
        // $gestiones = Gestion::where('ticket_id',61)->get();
        // return response()->json($gestiones);
    }

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
        $add_gestion = new Gestion($request->all());
        // if ($request->hasFile('image')) {
        //     $imageNames = [];    
        //     foreach ($request->file('image') as $image) {
        //         $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        //         $image->storeAs('images', $imageName);
        //         $imageNames[] = $imageName;
        //     }
        //     $concatenatedNames = implode(',', $imageNames);
        //     $add_gestion->image = $concatenatedNames;
        // }
         // Procesa las imágenes pegadas        
         if ($request->hasFile('pastedImages')) {
            $imageNamesPas = []; 
             foreach ($request->file('pastedImages') as $file) {
                $imageNamePas = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('images', $imageNamePas);
                $imageNamesPas[] = $imageNamePas;
             }
             $concatenatedNamesPas = implode(',', $imageNamesPas);
             $add_gestion->image = $concatenatedNamesPas;
         }
        
        if(!isset($request->cerrar) && !isset($request->reopen)){
            $add_gestion->status_id = 2; // 2 es el id del status en proceso
        }elseif(isset($request->cerrar)){
            $add_gestion->status_id = 4; //4 es el id del status Finalizado
        }elseif(isset($request->reopen)){
            $add_gestion->status_id = 5; //4 es el id del status Finalizado
        } 
        $add_gestion->save();

        if (isset($add_gestion->status_id)) {
            $update_ticket = Ticket::find($request->ticket_id);
            $update_ticket->status_id = $add_gestion->status_id;
            $update_ticket->last_updated_at = now();
           $update_ticket->update();
        }
        if (isset($add_gestion->category_id)) {
            $update_ticket = Ticket::find($request->ticket_id);
            $update_ticket->category_id = $request->category_id;
            $update_ticket->area_id = $request->area_id;
            $update_ticket->last_updated_at = now();
           $update_ticket->update();
        }
        /** */
        /**Obtener los datos de la persona que lo gestiono */
        $usuario = $request->user_id;  // que agrego la gestion
        // Obtener el departamento del ticket
        // $department = Department::find($request->department_id);
        $department = Department::find(21);
        // Suponiendo que `Ticket` es el modelo del ticket
        $ticket = Ticket::find($request->ticket_id);
        

        // Notificar a todos los usuarios del departamento
        if ($department) {
            foreach ($department->users as $user) {
                $user->notify(new GestionNotification($add_gestion,$ticket));
            }
        } else {
            Log::error('Departamento no encontrado: ' . $request->department_id);
            return response()->json(['message' => 'Departamento no encontrado'], 404);
        }
        /** */

        return response()->json([
            'message' => 'Message created successfully',
            'data' => $add_gestion
        ]);
    }
    // 
    public function store1(Request $request)    
    {
        // dd( $request->all());
        
        $validatedData = $request->validate([
            'ticket_id' => 'required', 
            'coment' => 'required', 
            'user_id' => 'required',
            'area_id' => 'required',
            'category_id' => 'required',              
            'image.*' => 'image|mimes:jpeg,png,jpg,gif' 
            //'image|mimes:jpeg,png,jpg,gif|max:2048' // Validar que cada archivo sea una imagen
        ]);
        $add_gestion = new Gestion($validatedData);
        if ($request->hasFile('image')) {
            $imageNames = [];    
            foreach ($request->file('image') as $image) {
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('images', $imageName);
                $imageNames[] = $imageName;
            }
            $concatenatedNames = implode(',', $imageNames);
            $add_gestion->image = $concatenatedNames;
        }
        
        if(!isset($request->cerrar) && !isset($request->reopen)){
            $add_gestion->status_id = 2; // 2 es el id del status en proceso
        }elseif(isset($request->cerrar)){
            $add_gestion->status_id = 4; //4 es el id del status Finalizado
        }elseif(isset($request->reopen)){
            $add_gestion->status_id = 5; //4 es el id del status Finalizado
        }      
        // return $request;  
        $add_gestion->save();

        if (isset($add_gestion->status_id)) {
            $update_ticket = Ticket::find($request->ticket_id);
            $update_ticket->status_id = $add_gestion->status_id;
            $update_ticket->category_id = $request->category_id;
            $update_ticket->area_id = $request->area_id;
           $update_ticket->update();
        }
        
        return redirect()->route('ticket.index')->with('success','El ticket fue Gestionado con exito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gestion $gestion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gestion $gestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gestion $gestion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gestion $gestion)
    {
        //
    }
}
