<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(){
        
        
        $users = User::latest()->paginate();
        return view('User.index',['users' => $users]);
    }
    public function create(){
        return view('user.create',['department' => Department::pluck('name','id')]);
    }
    
    public function store(){
        //
    }
// 

// 

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
