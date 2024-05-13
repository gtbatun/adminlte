<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Area;
use App\Models\Category;
use App\Models\Department;
use App\Models\Gestion;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
// se agregan para que funcione la opcion exportar
use App\Exports\TicketExport;
use Maatwebsite\Excel\Facades\Excel;


// 
use Illuminate\Support\Facades\Gate;
//
// use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class TicketController extends Controller
{
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
        

        return view('ticket.index',[
            'newTicket'=> new Ticket,
            'ticket' => $ticket
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ticket = new Ticket;
        return view('Ticket.create',
        [
            'areas' => Area::pluck('name','id'),
            'category' => Category::pluck('name','id'),
            'department' => Department::pluck('name','id'),
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
        //se agra la funcion o lo findorfail para que falle  en la busqueda de un ticket que no existe
        $h_gestiones = Gestion::where('ticket_id',$ticket->id )->get();
        //show para mostar el formulario de insert de gestiones de cada ticket
        return view('Gestion.create',[
            'areas' => Area::pluck('name','id'),
            'category' => Category::pluck('name','id'),
            'department' => Department::pluck('name','id'),
            'status' => Status::pluck('name','id'),
            'h_gestiones' => $h_gestiones,
            'ticket' => $ticket]);
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
