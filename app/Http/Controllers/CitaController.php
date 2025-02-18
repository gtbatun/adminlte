<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Ticket;

use App\Models\Gestion;
use App\Models\User;
use Illuminate\Http\Request;

use App\Notifications\GestionNotification;
use App\Models\Department;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('Cita.index');
    }

    public function unablefech(){
        $citas = Cita::select('start', 'end')->get();
        return response()->json($citas); 
    }



    public function fetchcitas(Request $request)
    {
        $citas = Cita::whereBetween('start', [$request->start, $request->end])->get();
    
        $events = $citas->map(function ($cita) {
            return [
                'id'    => $cita->id,
                'title' => $cita->title,
                'start' => $cita->start,
                'end'   => $cita->end,
                'description' => $cita->description,
                'color' => '#007bff' // Azul
            ];
        });
    
        return response()->json($events);
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
        $usuario = User::find($request->user_id);

        // Convertir la fecha de inicio a un objeto Carbon
        $startDate = Carbon::parse($request->start_date);

        //Calcular automáticamente `end_date` sumando 30 minutos
        $endDate = $startDate->copy()->addMinutes(30);

        $cita = new Cita();
        $cita->ticket_id = $request->ticket_id;
        $cita->title = $request->title;
        $cita->start = $request->start_date;
        $cita->end = $endDate;
        $cita->user_id = $request->user_id;

        // if($request->end_date){
        //     $cita->end = $request->end_date;
        // }

        $cita->save();

        $updateticket = Ticket::find($request->ticket_id);
        $updateticket->status_id = 7; 
        $updateticket->due_date = $request->start_date;
        $updateticket->update();

        $insert_gestion = new Gestion();
        $insert_gestion->ticket_id = $request->ticket_id;
        $insert_gestion->coment = 'Se agenda una cita el dia '. $request->start_date.', En el Horario '.$request->start_date.'. Cita agendada por el usuario '. $usuario->name;
        $insert_gestion->user_id = $request->user_id;
        $insert_gestion->status_id = 2;
        $insert_gestion->save();

        return response()->json(['success' => 'Cita creada', 'cita' => $cita]);
    }


    /**
     * Display the specified resource.
     */
    public function show(cita $cita)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cita $cita)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */   
    public function update(Request $request, $id)
    {   
        $usuario = Auth::user();
        // $usuario = User::find($user->id);        
        $startDate = Carbon::parse($request->start_date);// Convertir la fecha de inicio a un objeto Carbon
        $endDate = $startDate->copy()->addMinutes(30); //Calcular automáticamente `end_date` sumando 30 minutos

        $cita = Cita::findOrFail($id);
            $cita->start = $request->start_date;
            if($request->end_date){
                $cita->end = $request->end_date;
            }else{
                $cita->end = $endDate;
            }
        $cita->update();

        $insert_gestion = new Gestion();
            $insert_gestion->ticket_id = $cita->ticket_id;
            $insert_gestion->coment = 'Se Reagenda la cita para el dia '. $startDate.'. Cita modificada por el usuario '. $usuario->name;
            $insert_gestion->user_id = $usuario->id;
            // $insert_gestion->status_id = 2;
        $insert_gestion->save();

        // /****  Funciones para agregar notificaciones ****/

        // Usuario que agrega la gestión
        $dep_user_gestion = User::find($usuario->id);
        //Ticket que se esta contestando
        $ticket = Ticket::find($cita->ticket_id);
        
        if ($dep_user_gestion->department_id == $ticket->department_id) {
            // Si el departamento que agrega el comentario es el mismo que el departamento del ticket, notificar al receptor
            $ticket->user->notify(new GestionNotification($insert_gestion,$ticket));
            $NotDepartment = null; // No se necesita notificar a ningún departamento adicional
        } else {
            // Si el departamento que agrega el comentario es diferente al departamento del ticket, notificar al creador
            $NotDepartment = $ticket->department_id;
        }

        if ($NotDepartment) {
            $department = Department::find($NotDepartment);

            // Notificar a todos los usuarios del departamento
            if ($department) {

                foreach ($department->users as $user) {
                    $user->notify(new GestionNotification($insert_gestion,$ticket));
                }
            } else {
                Log::error('Departamento no encontrado: ' . $request->department_id);
                return response()->json(['message' => 'Departamento no encontrado'], 404);
            }
        }


        return response()->json(['success' => 'Cita actualizada correctamente','cita'=>$cita]);
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cita = Cita::find($id);
    
        if (!$cita) {
            return response()->json(['error' => 'Cita no encontrada'], 404);
        }
    
        $cita->delete();
    
        return response()->json(['success' => 'Cita eliminada']);
    }
    
}
