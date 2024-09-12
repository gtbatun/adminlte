<?php

namespace App\Http\Controllers;

use App\Models\Gestion;
use App\Models\Ticket; //se agrega para poder actualizar el status del ticket
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Notifications\GestionNotification;
use App\Models\Department;
use App\Models\User;
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
        
        // if(!isset($request->cerrar) && !isset($request->reopen)){
        //      $add_gestion->status_id = 2; // 2 es el id del status en proceso
        //     /** Agregar una validacion extra en la seccion de tickets cerrados, para no abrir de nuevo el ticket */
        // }elseif(isset($request->cerrar)){
        //     $add_gestion->status_id = 4; //4 es el id del status Finalizado
        // }elseif(isset($request->reopen)){
        //     $add_gestion->status_id = 5; //4 es el id del status Finalizado
        // } 

       
            //si el estatus es nuevo, aasignar un estatus
            if(!isset($request->cerrar) && !isset($request->reopen)){
                if($request->status_id != 4 ){
                    $add_gestion->status_id = 2; // 2 es el id del status en proceso
                }
            }elseif(isset($request->cerrar)){
                $add_gestion->status_id = 4; //4 es el id del status Finalizado
            }elseif(isset($request->reopen)){
                $add_gestion->status_id = 5; //4 es el id del status Finalizado
            } 

        




        $add_gestion->save();

        if (isset($add_gestion->status_id)) {
            $update_ticket = Ticket::find($request->ticket_id);
            $update_ticket->status_id = $add_gestion->status_id;
            // $update_ticket->last_updated_at = now();
           $update_ticket->update();
        }
        /** Revisar el cambio de categoria y area de los tickets */
        if (isset($request->category_id)) {
            $update_ticket = Ticket::find($request->ticket_id);
            $update_ticket->category_id = $request->category_id;
            $update_ticket->area_id = $request->area_id;
            // $update_ticket->last_updated_at = now();
           $update_ticket->update();
        }


        /***
         * determinar los usuarios a notificar
         * ticket->department_id --- departamento al que se asigno el ticket
         * ticket->type --- departamento que creo el ticket
         * ticket->user_id --- usuario que creo el ticket 
         */


        // Usuario que agrega la gestión
        $dep_user_gestion = User::find($request->user_id);
        //Ticket que se esta contestando
        $ticket = Ticket::find($request->ticket_id);
      
        if ($dep_user_gestion->department_id == $ticket->department_id) {
            // Si el departamento que agrega el comentario es el mismo que el departamento del ticket, notificar al receptor
            $ticket->user->notify(new GestionNotification($add_gestion,$ticket));
            $NotDepartment = null; // No se necesita notificar a ningún departamento adicional
        } else {
            // Si el departamento que agrega el comentario es diferente al departamento del ticket, notificar al creador
            $NotDepartment = $ticket->department_id;
        }

        Log::error('reslutado: '.$NotDepartment.'
        ,quienagregalagestion: '.$dep_user_gestion->department_id.' 
        ,depasignadoticket: '.$ticket->department_id.'
        ,tickettype: '.$ticket->type.'
        ticketdeparment_id: '.$ticket->department_id );

        
        /** */
        /**Obtener los datos de la persona que lo gestiono */
        // Obtener el departamento del ticket
        // $department = Department::find($NotDepartment);
        // Suponiendo que `Ticket` es el modelo del ticket

        // Obtener los datos del departamento asignado si se necesita notificar

        if ($NotDepartment) {
            $department = Department::find($NotDepartment);

            // Notificar a todos los usuarios del departamento
            if ($department) {

                foreach ($department->users as $user) {
                    $user->notify(new GestionNotification($add_gestion,$ticket));
                }
            } else {
                Log::error('Departamento no encontrado: ' . $request->department_id);
                return response()->json(['message' => 'Departamento no encontrado'], 404);
            }
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

    /**------------------------------ */
    public function store33(Request $request)    
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
            // $update_ticket->last_updated_at = now();
           $update_ticket->update();
        }
        if (isset($add_gestion->category_id)) {
            $update_ticket = Ticket::find($request->ticket_id);
            $update_ticket->category_id = $request->category_id;
            $update_ticket->area_id = $request->area_id;
            // $update_ticket->last_updated_at = now();
           $update_ticket->update();
        }
        //  departamentos involucrados en el ticket ['producion','soporte']
        // usuario que contesta ['dayana']
        // comparar el departamento quien contesta
        // notificacion 
        /**
         * notificar todos los integrantes del departamento cuyando contesten un ticket
         * notificar solo al usuario que creo el ticket
         * */

        /***
         * determinar los usuarios a notificar
         * ticket->department_id --- departamento al que se asigno el ticket
         * ticket->type --- departamento que creo el ticket
         * ticket->user_id --- usuario que creo el ticket 
         */


        //USUARIO QUE AGREGA LA GESTION
        $dep_user_gestion = User::find($request->user_id);
        //Ticket que se esta contestando
        $ticket = Ticket::find($request->ticket_id);
      
        if ($dep_user_gestion->department_id == $ticket->department_id) {
            // Si el departamento que agrega el comentario es el mismo que el departamento del ticket, notificar al receptor
            $NotDepartment = $ticket->type; // Suponiendo que el receptor tiene su propio campo en el ticket
        } else {
            // Si el departamento que agrega el comentario es diferente al departamento del ticket, notificar al creador
            $NotDepartment = $ticket->department_id;
        }

        Log::error('reslutado: '.$NotDepartment.'
        ,quienagregalagestion: '.$dep_user_gestion->department_id.' 
        ,depasignadoticket: '.$ticket->department_id.'
        ,tickettype: '.$ticket->type.'
        ticketdeparment_id: '.$ticket->department_id );

        
        /** */
        /**Obtener los datos de la persona que lo gestiono */
        // Obtener el departamento del ticket
        $department = Department::find($NotDepartment);
        // Suponiendo que `Ticket` es el modelo del ticket
        

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

    /** -------------------------------------------------- */

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
