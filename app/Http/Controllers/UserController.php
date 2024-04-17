<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;



use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(){
        
        $users = User::latest()->paginate();
        return view('User.index',['users' => $users]);
    }
    public function create(){
        $user = new User();
        return view('user.create',['department' => Department::pluck('name','id'),'user' => $user]);
    }
    
    public function store(Request $request){
        // return $request;
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'department_id' => 'required',
            'extension' => 'numeric',
            'is_admin' => 'required',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
                       
        ]);
        $user = new User();
        if($request->file('image')){
            $images = $request->file('image');
            $imageNames = [];
            $errors = [];
            foreach($images as $image){                
                $imageName = time() . ' - ' . $image->getClientOriginalName();
                    if($image->isValid()){
                        $image->storeAs('images/user',$imageName);
                        $imageNames[] = $imageName;  
                    }
                }            
            $concatenatedNames = implode(', ', $imageNames);
            $user->image = $concatenatedNames;
        }

        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department_id;
        $user->extension = $request->extension;
        $user->password = $request->password;
        $user->is_admin = $request->is_admin; 
        $user->save();


        // Area::create($request->all());
        return redirect()->route('user.index')->with('success', 'Nuevo Usuario creado exitosamente');

    } 

    public function edit(User $user){
        return view('User.edit',
        [
            'user' => $user, 
            'department' => Department::pluck('name','id'),
        ]);
    }
    

    public function show(){
       
        // return view('User.profile');
        return "sdsdsdsdsdsdsdsdsdsdsds";
    }

    public function update(Request $request, User $user)
    {
        $user->fill($request->validate([
            'name' => 'required',
            'email' => 'required',
            'department_id' => 'required',
            'extension' => 'min:2',
        ]));

        if($request->file('image')){
            $images = $request->file('image');
            $imageNames = [];
            $errors = [];
            foreach($images as $image){                
                $imageName = time() . ' - ' . $image->getClientOriginalName();
                    if($image->isValid()){
                        $image->storeAs('images/user',$imageName);
                        $imageNames[] = $imageName;  
                    }
                }            
            $concatenatedNames = implode(', ', $imageNames);
            $user->image = $concatenatedNames;
        }
       
        $user->save();
        return redirect()->route('user.index', $user)->with('success','El Usuario fue actualizado con exito');
    
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'El Usuario fue eliminado exitosamente');
    }
    
}
