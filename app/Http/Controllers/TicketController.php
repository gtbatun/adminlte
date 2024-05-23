<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Area;
use App\Models\Category;
use App\Models\Department;
use App\Models\Gestion;
use App\Models\Status;
use App\Models\User;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
// se agregan para que funcione la opcion exportar
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;

 
use Illuminate\Support\Facades\Gate;
//

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    /** constructor para ver y solicitar que todos esten autenticados y evitar error de datatable */ 
    public function __construct(){
        $this->middleware('auth');
    }

    /** */   

    /*
    /** funcion para refrescar la tabla de tickets sin recargar la pagina */ 

    public function data()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $user = Auth::user();
        
        if($user->is_admin == 10){
            $tickets = Ticket::with('area','category','status','department')
            ->where('status_id', '!=', 4 )
            ->get();
        }else{ 
            $tickets = Ticket::with('area','category','status','department')
                ->where(function($query) {
                    $query->where('department_id', auth()->user()->department_id)
                        ->orWhere('type','=', auth()->user()->department_id);
                })
                ->where('status_id', '!=', 4)
                ->get();
                }
        // 

        // $tickets = Ticket::with('area','category','status','department')->get();
        $tickets = $tickets->map(function($ticket){
            $userDepartmentId = auth()->user()->department_id;
            $typeString = ($ticket->department_id == $userDepartmentId) ?  'Asignado':'Creado'; // En este caso, ambos retornos son 'creado'
            $typeColor = ($ticket->department_id == $userDepartmentId) ?  'rgba(209, 90, 13)' : 'rgba(5, 47, 233)' ;
            
            
            $color = ($ticket->status->name == 'Nuevo') ? 'rgba(255, 0, 0, 0.2)' : 'rgba(0, 0, 0, 0.0)';
            $color = ($ticket->status->name == 'En proceso') ? 'rgba(255, 165, 0, 0.2)' : $color;

            // $typeColorback = ($ticket->status == 'Nuevo') ? 'rgba(0, 0, 255, 0.2)' : 'rgba(0, 255, 0, 0.2)'; // Azul para Asignado, Verde para Creado

            return [
                'id' => $ticket->id,
                'usuario' => $ticket->usuario->name,
                'title' => view('Ticket.Partials.title', ['ticket' => $ticket])->render(),
                'category' => $ticket->category->name,
                'sucursal' => $ticket->usuario->sucursal->name,
                'department' => $ticket->department->name,
                // 'type' => $ticket->type,
                'type' => $typeString,
                'typeColor' => $typeColor, // Include the color in the response
                'typeColorback' => $color, // Include the color in the response
                'area' => $ticket->area->name,
                'status' => $ticket->status->name,
                'created_at' => $ticket->created_at->diffForHumans(),
                'actions' => view('Ticket.Partials.actions', ['ticket' => $ticket])->render()
            ];
        });
        return response()->json(['data' => $tickets]);
    }
    /*** ontener las gestiones de lo cada ticket*/
    public function getGestiones(Ticket $ticket)
    {
        $h_gestiones1 = Gestion::where('ticket_id', $ticket->id)->with('usuario')->orderby('created_at','desc')->get();
        return response()->json($h_gestiones1);
    }

    
    /* **/

    public function getCategory(Request $request)
    {
        $area_id = $request->area_id;
        $area = Area::find($area_id);

        if (!$area) {
            return response()->json(['error' => 'Area not found'], 404);
        }

        $category = $area->category;

        return response()->json($category);
    }
    

    /**
     * Display a listing of the resource.
     */
    // public function export($fechaInicio, $fechaFin){
        public function export(){
        // return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'Tickets.xlsx');        
        // return Excel::download(new TicketExport($fechaInicio, $fechaFin), 'tickets.xlsx');
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
        
        if($user->is_admin == 10){
            $ticket_clo = Ticket::with('area','category','status','department')
                ->where('status_id', '=', 4 )
                ->get();
        }else{            
                $ticket_clo = Ticket::with('area','category','status','department')
                ->where(function($query) {
                    $query->where('department_id', auth()->user()->department_id)
                        ->orWhere('type','=', auth()->user()->department_id);
                })
                ->where('status_id', '=', 4)
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
        // excluir sistemas y soporte, todos lo pueden ver sin excepcion
        $additionalDepartmentIds = [20,21]; // agregar en esta seccion los departamento que se desean visualizar
        $departamento1 = Department::whereIn('id', $additionalDepartmentIds)
                        // ->orWhereIn('id',$additionalDepartmentIds)
                        ->pluck('name', 'id');
                        
                        // $departamento = Department::where('sucursal_id', auth()->user()->sucursal_id)
                        // ->orWhereIn('id',$additionalDepartmentIds)
                        // ->pluck('name', 'id');
        
        $departamento = Department::where('sucursal_id', auth()->user()->sucursal_id)
        ->where('id',18) // poner un array si se desa poner mas departamentos
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
        // dd ($request->all());
        // return $request->all();
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'area_id' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
            'status_id' => 'required',
            'user_id' => 'required',
            'image.*' => 'image|mimes:jpeg,png,jpg,gif' 
            //'image|mimes:jpeg,png,jpg,gif|max:2048' // Validar que cada archivo sea una imagen
        ]);
        $add_ticket = new Ticket($request->all());
    
        if ($request->hasFile('image')) {
            $imageNames = [];    
            foreach ($request->file('image') as $image) {                
                // $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();                
                // $imageName = time() . '_' . $image->getClientOriginalName() . '.' . $image->getClientOriginalExtension();
                $imageName = time() . '_' . $image->getClientOriginalName();
                // $image = ImageManager::make($image)->resize(300, 200)->encode();

                $image->storeAs('images', $imageName);
                // $fullpach = storage_path('app/public/images/'. $pach);

                
                $imageNames[] = $imageName;
            }
            $concatenatedNames = implode(',', $imageNames);
            $add_ticket->image = $concatenatedNames;
        }
      

        
        $add_ticket->save();
        return response()->json(['message' => 'Ticket created successfully','redirect_to' => route('ticket.index')], 200); 
        
   }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        /**verificar que departamento tiene el ticket */
        $department_id = $ticket->department_id;
        $area_id = $ticket->area_id;
        $ticket = Ticket::with('usuario','department','category','area')->findOrFail($ticket->id);
        //se agra la funcion o lo findorfail para que falle  en la busqueda de un ticket que no existe
        $h_gestiones = Gestion::where('ticket_id',$ticket->id )->with('usuario')->orderBy('created_at', 'desc')->get();
        //show para mostar el formulario de insert de gestiones de cada ticket
        // return $h_gestiones;
        return view('Gestion.create',[
            'areas' => Area::where('department_id',$department_id)->pluck('name','id'), // se agrega el where para limitar las areas y solo mostara los pertenecientes al departamento
            'category' => Category::where('area_id',$area_id)->pluck('name','id'),
            'department' => Department::pluck('name','id'),
            'status' => Status::pluck('name','id'),
            'h_gestiones' => $h_gestiones,
            'ticket' => $ticket
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        // $this->authorize('update',$ticket);
       
        return view('Ticket.edit', [
            'areas' => Area::pluck('name','id'),
            'category' => Category::pluck('name','id'),
            'department' => Department::pluck('name','id'),
            'status' => Status::pluck('name','id'),
            'ticket' => $ticket]);
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
