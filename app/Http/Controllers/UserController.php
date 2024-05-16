<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(){
        
        $users = User::latest()->paginate(10);
        return view('User.index',['users' => $users]);
    }
    public function create(){
        $user = new User();
        return view('User.create',['department' => Department::pluck('name','id'),'user' => $user]);
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
            $imageName = time() . ' - ' . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('images/user', $imageName);
            $user->image = $imageName;
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
        // $this->authorize('update', $user);
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
    public function updatepassword(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => ['required','string','min:8'],
        ]);

        $user = User::findOrFail($request->user_id);

        // Actualizar la contraseña
        $user->password = Hash::make($request->password);
        $user->update();
    
        return redirect()->route('user.index')->with('success','Contraseña actualizada con exito');
    }

    public function update(Request $request, User $user)
    {
        $oldImage = $user->image;
        // $this->authorize('update', $user);

        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'department_id' => 'nullable|integer',
            'extension' => 'nullable|min:2',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Asegúrate de validar también la imagen
        ]);

        $user->fill($validatedData);       

        if ($request->file('image')) {
            if ($oldImage) {
                Storage::delete('images/user/'. $oldImage);
            }
            $imageName = time() . '-' . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('images/user', $imageName);
            
            $user->image = $imageName;
        }

        
        // $user->save();

        // return redirect()->route('user.index')->with('success', 'El Usuario fue actualizado con éxito');

        $user->save();
        $user_log = Auth::user();
        if($user_log->isAdmin()){
            return redirect()->route('user.index')->with('success','El Usuario fue actualizado con exito');
        }else{
            return redirect()->route('user.edit',$user )->with('success','Datos actualizado con exito');
        }
    }


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'El Usuario fue eliminado exitosamente');
    }
    
}
