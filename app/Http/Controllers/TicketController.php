<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Area;
use App\Models\Category;
use App\Models\Department;
use App\Models\Gestion;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\TicketNotification;
use Illuminate\Support\Facades\Log;

use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /** constructor para ver y solicitar que todos esten autenticados y evitar error de datatable */ 
    public function __construct(){
        $this->middleware('auth');
    }

    /** */  
    /**funcion para verificar si algun ticket ya se le modifico la fecha de update */
    public function checkUpdates()
    {
        $userId = auth()->user()->department_id;
        $latestUpdate = Ticket::where('department_id', $userId)
                            ->orderBy('last_updated_at', 'desc')
                            ->first();

        return response()->json(['last_updated_at' => optional($latestUpdate)->last_updated_at]);
    } 

    /*
    /** funcion para refrescar la tabla de tickets sin recargar la pagina */ 

    public function data()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }        
        $user = Auth::user();        
        if($user->is_admin == 10 || $user->is_admin == 5){
            $tickets = Ticket::with('area','category','status','department')
            ->where('status_id', '!=', 4 )
            ->get();
        }else{ 
            $tickets = Ticket::with(['area', 'category', 'status', 'department'])
                ->where(function($query) {
                    $query->where('department_id', auth()->user()->department_id)
                        ->orWhere('type', auth()->user()->department_id);
                })
                ->where('status_id', '!=', 4)
                ->whereHas('user', function($query) {
                    $query->where('sucursal_id', auth()->user()->sucursal_id);
                })
                ->get();
            }        
        // $tickets = Ticket::with('area','category','status','department')->get();
        $tickets = $tickets->map(function($ticket){
            $userDepartmentId = auth()->user()->department_id;
            // $typeString = ($ticket->department_id == $userDepartmentId) ?  'Asignado':'Creado'; // En este caso, ambos retornos son 'creado'
            // $typeColor = ($ticket->department_id == $userDepartmentId) ?  'rgba(209, 90, 13)' : 'rgba(5, 47, 233)' ;

            $user = auth()->user();           
            if ($ticket->user_id == $user->id) {
                $type = '<strong>Creado</strong>'; 
                
                $color = 'rgba(46, 204, 133,0.4)'; // Azul
            } elseif ($ticket->department_id == $user->department_id) {
                $type = '<strong>Asigado</strong>';
                $color = 'rgba(231, 76, 60,0.4)'; // Naranja
            } else {
                $type = '<strong>'.$ticket->department->name.'</strong>';
                // $color = 'rgba(147, 149, 179,0.4)'; // Gris
                $color = '';
            }            
            
            $color1 = ($ticket->status->name == 'Nuevo') ? 'rgba(255, 0, 0, 0.2)' : 'rgba(0, 0, 0, 0.0)';
            $typeColorback = ($ticket->status->name == 'En proceso') ? 'rgba(255, 165, 0, 0.2)' : $color1;

            /** script para seleccionar el o obtener la ultima persona que contesto el tikcet */
            // Obtener el ticket
                $ticket = Ticket::findOrFail($ticket->id);
                // Obtener la última gestión del ticket
                $latestGestion = Gestion::where('ticket_id', $ticket->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                // Obtener el creador del ticket
                $creatorId = $ticket->user_id;
                // Obtener el departamento asignado
                $assignedDepartment = $ticket->assigned_department;
                // Obtener el usuario actual
                $currentUser = Auth::user();
                // Determinar el mensaje
                $messageStatus = '';
                if ($latestGestion) {
                    // $gestionTime = $latestGestion->created_at->format('d/m/Y H:i'); // Formato de fecha y hora
                    $gestionTime = $latestGestion->created_at; // Formato de fecha y hora        
                    if ($latestGestion->user_id == $creatorId) {
                        $messageStatus = 'Enviado';
                        $gestionTime;
                        $messageClass = 'status-enviado';
                    } elseif ($latestGestion->department_id == $assignedDepartment) {
                        $messageStatus = 'Contestado';
                        $gestionTime;
                        $messageClass = 'status-respondido';
                    } else {
                        $messageStatus = 'Pendiente';
                        $gestionTime;
                        $messageClass = 'status-pendiente';
                    }
                } else {
                    $messageStatus = 'Sin respuesta';
                    $messageClass = 'status-sin-respuesta';
                    $gestionTime = $ticket->created_at; // Formato de fecha y hora
                }

                $latestGestionUserFirstName = $latestGestion ? explode(' ', $latestGestion->usuario->name)[0] : '';

                // Si el usuario actual no es el creador y no pertenece al departamento asignado
                if ($currentUser->id != $creatorId && $currentUser->department_id != $assignedDepartment) {
                    if ($latestGestion) {
                        $messageStatus = 'Última gestión por: '. $latestGestionUserFirstName;
                        $gestionTime;
                        $messageClass = 'status-ultima-gestion';
                    } else {
                        $messageStatus = 'Sin respuesta';
                        $messageClass = 'status-sin-respuesta';
                        $gestionTime = $ticket->created_at; // Formato de fecha y hora
                    }
                }
/**------------------------------------------------------------------------------------------------------------------------------- */
       // $typeColorback = ($ticket->status == 'Nuevo') ? 'rgba(0, 0, 255, 0.2)' : 'rgba(0, 255, 0, 0.2)'; // Azul para Asignado, Verde para Creado

            return [
                'id' => $ticket->id,
                // 'id' => $messageStatus,
                // 'title' => view('Ticket.Partials.title', ['ticket' => $ticket])->render(),
                'title' => view('Ticket.Partials.title', ['ticket' => $ticket,'messageStatus' => $messageStatus,'gestionTime' => $gestionTime,'messageClass' => $messageClass])->render(),
                'category' => view('Ticket.Partials.dep', ['ticket' => $ticket])->render(),
                // 'category' => $ticket->category->name,
                'sucursal' => $ticket->usuario->sucursal->name,
                // 'department' => $ticket->department->name,
                'type' => $type,
                // 'type' => $typeString,
                'typeColor' => $color, // Include the color in the response
                'typeColorback' => $typeColorback, // Include the color in the response
                'area' => $ticket->area->name,
                'status' => $ticket->status->name,
                // 'created_at' => $ticket->created_at->diffForHumans(),
                'actions' => view('Ticket.Partials.actions', ['ticket' => $ticket])->render()
            ];
            // return $messageStatus;
        });
        
        /**Fucnion para retornar el ultimo gestion, si es tuyo o ya fue respondido */
        
        return response()->json(['data' => $tickets]);        
    }
    /*** ontener las gestiones de lo cada ticket*/
    public function getGestiones(Ticket $ticket)
    {
        // $h_gestiones1 = Gestion::where('ticket_id', $ticket->id)->with('usuario')->orderby('created_at','asc')->get();

        // $h_gestiones1 = Gestion::where('ticket_id',$ticket->id )->get();
        $h_gestiones1 = Gestion::where('ticket_id', $ticket->id)
                            ->with(['usuario:id,name,email,image'])
                            ->select('id', 'ticket_id', 'coment', 'image', 'user_id', 'created_at', 'status_id')
                            ->get();
        return response()->json($h_gestiones1);        
    }    

    /**
     * Display a listing of the resource.
     */
    // public function export($fechaInicio, $fechaFin){
        public function export(){
        return Excel::download(new TicketExport('2024-04-15', '2024-04-19'), 'Tickets.xlsx');
        
        }    

    public function showReport(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        return view('Ticket.report', compact('startDate', 'endDate', 'ticket'));
        
        // return view('ticket.report');
    }
    public function closed(){
        $user = Auth::user();
        
        if($user->is_admin == 10 || $user->is_admin == 5){
            $ticket_clo = Ticket::with('area','category','status','department')
                ->where('status_id', '=', 4 )
                ->get();
        }else{ 
                $ticket_clo = Ticket::with(['area', 'category', 'status', 'department'])
                ->where(function($query) {
                    $query->where('department_id', auth()->user()->department_id)
                        ->orWhere('type', auth()->user()->department_id);
                })
                ->where('status_id', '=', 4)
                ->whereHas('user', function($query) {
                    $query->where('sucursal_id', auth()->user()->sucursal_id);
                })
                ->get();  
        }       

        return view('Ticket.closed',[
            'ticket' => $ticket_clo           
        ]);
    }

    public function index()
    { 
        $user = Auth::user();
        
        if($user->is_admin == 10){
            $ticket = Ticket::with('area','category','status','department')
                ->latest()
                ->paginate();
        }else{
            $ticket = Ticket::with('area','category','status','department')
               // ->where('user_id', $user->id) // Filtrar por el ID del usuario actual                
                ->where('department_id', '=', $user->department_id )
                ->where('status_id', '!=', 4 )
                ->latest()
                ->paginate();
        }
        

        // return view('Ticket.index',[
        //     'newTicket'=> new Ticket,
        //     'ticket' => $ticket
            
        // ]);
        return view('Ticket.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->sucursal_id == 1){
            // $departamento = Department::where('enableforticket','!=','null')
            $additionalDepartmentIds = [18,20,21,23];
            $departamento = Department::whereIn('id',$additionalDepartmentIds)
            ->pluck('name', 'id');
        }else{
            // $departamento = Department::where('multi','!=','null')
            $additionalDepartmentIds = [20,21];
            $departamento = Department::whereIn('id',$additionalDepartmentIds)
            ->pluck('name', 'id');
        }
        $ticket = new Ticket;
        return view('Ticket.create',
        [
            'areas' => Area::pluck('name','id'),
            'category' => Category::pluck('name','id','area_id'),
            'department' => $departamento,
            'status' => Status::pluck('name','id'),
            'ticket' => $ticket
        ]);
    }
    public function create1()
    {
        // excluir sistemas y soporte, todos lo pueden ver sin excepcion
        $additionalDepartmentIds = [20,21]; // agregar en esta seccion los departamento que se desean visualizar
        $departamento1 = Department::whereIn('id', $additionalDepartmentIds)
                        ->pluck('name', 'id');
                        
                        // $departamento = Department::where('sucursal_id', auth()->user()->sucursal_id)
                        // ->orWhereIn('id',$additionalDepartmentIds)
                        // ->pluck('name', 'id');
        $dep_enable_ticket= [18,23]; 
        $departamento = Department::where('sucursal_id', auth()->user()->sucursal_id)
        ->whereIn('id',$dep_enable_ticket) // poner un array si se desa poner mas departamentos
        ->orWhereIn('id',$additionalDepartmentIds)
            ->pluck('name', 'id');
        $ticket = new Ticket;
        return view('Ticket.create',
        [
            'areas' => Area::pluck('name','id'),
            'category' => Category::pluck('name','id','area_id'),
            'department' => $departamento,
            // 'department' => Department::pluck('name','id'),
            'status' => Status::pluck('name','id'),
            'ticket' => $ticket
        ]);
    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'area_id' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'status_id' => 'required',
            'user_id' => 'required',
            'type' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif' 
        ]);

        // Crear una nueva instancia de Ticket con los datos validados
        $add_ticket = new Ticket($validatedData);

        // Manejar la subida de imágenes si existen
        if ($request->hasFile('image')) {
            $imageNames = [];    
            foreach ($request->file('image') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('images', $imageName);
                $imageNames[] = $imageName;
            }
            $concatenatedNames = implode(',', $imageNames);
            $add_ticket->image = $concatenatedNames;
        }

        // Guardar el ticket en la base de datos
        $add_ticket->save();
        
        // Obtener el departamento del ticket
        $department = Department::find($request->department_id);

        // Notificar a todos los usuarios del departamento
        if ($department) {
            foreach ($department->users as $user) {
                $user->notify(new TicketNotification($add_ticket));
            }
        } else {
            Log::error('Departamento no encontrado: ' . $request->department_id);
            return response()->json(['message' => 'Departamento no encontrado'], 404);
        }
        // $user->notify(new TicketNotification($add_ticket));

        // Retornar una respuesta exitosa
        return response()->json(['message' => 'Ticket created successfully', 'redirect_to' => route('ticket.index')], 200);
    } catch (\Exception $e) {
        // Loguear el error
        Log::error('Error al crear el ticket: ' . $e->getMessage());
        return response()->json(['message' => 'Error al crear el ticket', 'error' => $e->getMessage()], 500);
    }
}
    


    /**
     * Display the specified resource.
     */
    public function show2(Ticket $ticket)
    {
        $ticket = Ticket::with('department','category','area')->findOrFail($ticket->id);
        //se agra la funcion o lo findorfail para que falle  en la busqueda de un ticket que no existe
        
        $h_gestiones = Gestion::where('ticket_id',$ticket->id )->get();
        return view('Gestion.create',[
            'areas' => Area::where('department_id',$ticket->department_id)->pluck('name','id'), 
            'category' => Category::where('area_id',$ticket->area_id)->pluck('name','id'),
            'h_gestiones' => $h_gestiones,
            'ticket' => $ticket
        ]);
    }
    public function show(Ticket $ticket)
    {
        // Utiliza Eager Loading para cargar relaciones necesarias
        $ticket = Ticket::with(['department:id,name', 'category:id,name', 'area:id,name'])
                        ->select('id', 'department_id', 'category_id','status_id', 'area_id', 'user_id', 'title', 'description', 'created_at','image')
                        ->findOrFail($ticket->id);

        // Obtiene las áreas y categorías relacionadas con el departamento y área del ticket
        $areas = Area::where('department_id', $ticket->department_id)
                    ->pluck('name', 'id');
        
        $category = Category::where('area_id', $ticket->area_id)
                            ->pluck('name', 'id');

        // Retorna la vista con los datos necesarios
        // return view('Gestion.create', compact('areas', 'category', 'h_gestiones', 'ticket'));
        return view('Gestion.create', compact('areas', 'category', 'ticket'));
    }
    public function showtest(Ticket $ticket)
    {
        /**verificar que departamento tiene el ticket */
        $department_id = $ticket->department_id;
        $area_id = $ticket->area_id;
        $ticket = Ticket::with('department','category','area')->findOrFail($ticket->id);
        //se agra la funcion o lo findorfail para que falle  en la busqueda de un ticket que no existe
        
        $h_gestiones = Gestion::where('ticket_id',$ticket->id )->get();
        // $h_gestiones = Gestion::where('ticket_id',$ticket->id )->with('usuario')->get();        
        // $ticket = Ticket::with('usuario','department','category','area')->findOrFail($ticket->id);
        // $h_gestiones = Gestion::where('ticket_id',$ticket->id )->with('usuario')->orderBy('created_at', 'desc')->get();
        //show para mostar el formulario de insert de gestiones de cada ticket
        // return $h_gestiones;
        return view('Gestion.create',[
            'areas' => Area::where('department_id',$department_id)->pluck('name','id'), // se agrega el where para limitar las areas y solo mostara los pertenecientes al departamento
            'category' => Category::where('area_id',$area_id)->pluck('name','id'),
            'department' => Department::pluck('name','id'),
            'status' => Status::pluck('name','id'),
            'h_gestiones' => $h_gestiones
            ,
            'ticket' => $ticket
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    /** -------------------------------------------------------------------------------------- */
    public function getAreas($departamentoId) {
        $areas = Area::where('department_id', $departamentoId)->get();
        return response()->json($areas);
    }
    
    public function getCategorias($areaId) {
        $categorias = Category::where('area_id', $areaId)->get();
        return response()->json($categorias);
    }    
    
    /* **/
    public function getCategory1(Request $request)
    {
        return $request;
        $area_id = $request->area_id;
        $area = Area::find($area_id);
        if (!$area) {
            return response()->json(['error' => 'Area not found'], 404);
        }
        $category = $area->category;
        
        // return response()->json($category);
    }
    public function getCategory(Request $request)
    {
        $area_id = $request->input('area_id');
        $category = Category::where('area_id', $area_id)->get();
        return response()->json($category);
    }

    /** -------------------------------------------------------------------------------------- */

    public function edit(Ticket $ticket)
    {
        if(auth()->user()->sucursal_id == 1){
            // $departamento = Department::where('enableforticket','!=','null')
            $additionalDepartmentIds = [18,20,21,23];
            $departamento = Department::whereIn('id',$additionalDepartmentIds)->get();
        }else{
            // $departamento = Department::where('multi','!=','null')
            $additionalDepartmentIds = [20,21];
            $departamento = Department::whereIn('id',$additionalDepartmentIds)->get();
        }
        $ticket = Ticket::find($ticket->id);
        $department = $departamento;
        $areas = $ticket->department  ? $ticket->department->areas : collect();
        $categorias = $ticket->area ? $ticket->area->category : collect();
        // return $department;
        return view('Ticket.edit', compact('ticket','department','areas','categorias'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // dd( $request->all());
        // $this->authorize('update',$ticket); // para autorizar o restringir la actualizacion del tixcket

        $ticket->fill($request->validate([
                'title' => 'required',
                'description' => 'required',
                'area_id' => 'required',
                'department_id' => 'required',
                'category_id' => 'required',
                'status_id' => 'required',
                'user_id' => 'required',
        ]));
        // inicio de procesado de imagenes
        
        if($request->file('image')){
            $images = $request->file('image');
            $imageNames = [];
            $errors = [];
            foreach($images as $image){                
                $imageName = time() . ' - ' . $image->getClientOriginalName();
                    if($image->isValid()){
                        $image->storeAs('images',$imageName);
                        $imageNames[] = $imageName;  
                    }
                }            
            $concatenatedNames = implode(',', $imageNames);
            $ticket->image = $concatenatedNames;
        }
        // fin de seccion de lamacenamiento y procesado de imagenes
        $ticket->save();
        return redirect()->route('ticket.index', $ticket)->with('success','El ticket fue actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        // $this->authorize('destroy',$ticket);
        $ticket->delete();
        return redirect()->route('ticket.index')->with('success', 'Ticket Eliminado exitosamente');
    }
    
}
