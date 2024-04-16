<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;

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


    public function edit(User $user){
        // $user = User::findorFail($user);
        return view('User.edit',['user' => $user]);
    }

    public function show(){
       
        return view('User.profile');

    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all()); 
        return redirect()->route('user.index', $user)->with('success','El Usuario fue actualizado con exito');
    
    }
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'El Usuario fue eliminado exitosamente');
    }
    
}
