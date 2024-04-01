<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Area;
use App\Models\Category;
use App\Models\Department;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        
        // $ticket = Ticket::with('area','category','status','department')->latest()->paginate(5);
        // return view('ticket.index',['ticket' => $ticket]);

        return view('ticket.index',[
            'newTicket'=> new Ticket,
            'ticket' => Ticket::with('area','category','status','department')->latest()->paginate(5)
        ]);
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
                // if (!$image->isValid()) {
                //     return response()->json(['error' => 'Invalid image file.'], 400); 
                // }
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->storeAs('images', $imageName);
                $imageNames[] = $imageName;
            }
            $concatenatedNames = implode(', ', $imageNames);
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
        return "hola desde el show de";
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
