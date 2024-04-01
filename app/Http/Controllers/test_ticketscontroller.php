<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Area;
use App\Models\Category;
use App\Models\Department;
use App\Models\Status;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticket = Ticket::with('area','category','status','department')->latest()->paginate(5);
        return view('ticket.index',['ticket' => $ticket]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ticket = new Ticket;
        return view('ticket.create',
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
        // return $request->image;
        $add_ticket = new Ticket(
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'area_id' => 'required',
                'department_id' => 'required',
                'category_id' => 'required',
                'status_id' => 'required',
                'user_id' => 'required',
            ]));
        if($request->hasfile('image')){
            // $add_ticket->image = $request->file('image')->store('images');
            $images = $request->file('image');
            $imageNames = [];
            $errors = [];
            foreach($images as $image){                
                $imageName = time() . ' - ' . $image->getClientOriginalName();
                    if($image->isValid()){
                        $image->storeAs('images',$imageName);
                        $imageNames[] = $imageName;  
                    }else{
                        $errors[] = "Error al subir la imagen $imageName";
                    }
                }            
            $concatenatedNames = implode(', ', $imageNames);
            $add_ticket->image = $concatenatedNames;
        }

        $add_ticket->save();

        // Ticket::create($request->all());
         return redirect()->route('ticket.index')->with('status','El ticket fue creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', [
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
        dd( $request->all());

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
            $concatenatedNames = implode(', ', $imageNames);
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
        $ticket->delete();
        return redirect()->route('ticket.index')->with('success', 'Ticket Eliminado exitosamente');
    }
    
}
// 

public function store(Request $request)
    {
        // dd ($request->image);
        //return $request->all();
        
            $validatedData = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'area_id' => 'required',
                'department_id' => 'required',
                'category_id' => 'required',
                'status_id' => 'required',
                'user_id' => 'required',
            ]);
        $add_ticket = new Ticket($validatedData);
        
        if($request->hasFile('image')){
            
            $images = $request->file('image');
            $imageNames = [];
            $errors = [];
            foreach($images as $image){
                    if($image->isValid()){                
                        $imageName = time() . ' - ' . $image->getClientOriginalName();
                        $image->storeAs('images',$imageName);
                        $imageNames[] = $imageName;  
                    }else{
                        $errors[] = "Error al subir la imagen $image->getClientOriginalName()";
                    }
                }            
            $concatenatedNames = implode(', ', $imageNames);
            $add_ticket->image = $concatenatedNames;
        }

        // $add_ticket->save();
return $request->all();
        // Ticket::create($request->all());
        //  return redirect()->route('ticket.index')->with('status','El ticket fue creado exitosamente');
    }
// 




// --------------------------------------------------------------------------------------------------
<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Area;
use App\Models\Category;
use App\Models\Department;
use App\Models\Status;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticket = Ticket::with('area','category','status','department')->latest()->paginate(5);
        return view('ticket.index',['ticket' => $ticket]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ticket = new Ticket;
        return view('ticket.create',
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
        // dd ($request->image);
        //return $request->all();
        
            $validatedData = $request->validate([
                'title' => 'required',
                'description' => 'required',
                'area_id' => 'required',
                'department_id' => 'required',
                'category_id' => 'required',
                'status_id' => 'required',
                'user_id' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
        $add_ticket = new Ticket($validatedData);
        
            if($request->hasFile('image')){            
            $imageNames = [];
            $errors = [];
            foreach($request->file('image') as $image){
                    if($image->isValid()){                
                        $imageName = time() . ' - ' . $image->getClientOriginalName();
                        $image->storeAs('images',$imageName);
                        $imageNames[] = $imageName;  
                    }else{
                        $errors[] = "Error al subir la imagen $image->getClientOriginalName()";
                    }
                }            
            // $concatenatedNames = implode(', ', $imageNames);
            // $add_ticket->image = $concatenatedNames;
        
        }
        if (!empty($imageNames)) {
            $concatenatedNames = implode(', ', $imageNames);
            $add_ticket->image = $concatenatedNames;
        // } else {
            // return response()->json(['message' => 'Error al subir una o más imágenes', 'errors' => $errors], 400);
        }

        $add_ticket->save();
// return $request->all();
        // Ticket::create($request->all());
        //  return redirect()->route('ticket.index')->with('status','El ticket fue creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', [
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
        dd( $request->all());

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
            $concatenatedNames = implode(', ', $imageNames);
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
        $ticket->delete();
        return redirect()->route('ticket.index')->with('success', 'Ticket Eliminado exitosamente');
    }
    
}
