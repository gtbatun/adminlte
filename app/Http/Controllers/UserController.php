<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Sucursal;
use App\Models\Device;
use App\Models\Inventory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


use Yajra\DataTables\Facades\DataTables; //se agrego para las graficas segun

use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function searchUsers(Request $request)
    {
        $users = User::where('name', 'LIKE', '%' . $request->input('query') . '%')->get();
        return response()->json($users);
    }

    public function getUserDevices111($userId)
    {
        // Obtener los registros de Inventory con los IDs de device
    $devicesUser = Inventory::where('user_id', $userId)
                            ->where('enable', 1)
                            ->get(['id', 'device_id']); // Obtener los campos id (Inventory) y device_id

    // Obtener los detalles de los dispositivos desde la tabla Device
    $deviceIds = $devicesUser->pluck('device_id')->toArray();
    $devices = Device::whereIn('id', $deviceIds)->with('tipodevice')->get();

    // Combinar los datos de Inventory y Device
    $result = $devicesUser->map(function($inventory) use ($devices) {
    $device = $devices->firstWhere('id', $inventory->device_id);
    if ($device) {
    return [
        'inventory_id' => $inventory->id, // ID de Inventory
        'id' => $device->id, // ID de Device
        'name' => $device->name,        
        'user_id' => $device->user_id,
        'tipodevice' => $device->tipodevice,
        // Agrega otros campos del dispositivo según sea necesario
    ];
    } else {
    return [
        'inventory_id' => $inventory->id, // ID de Inventory
        'id' => null, // ID de Device
        'name' => null,
        'tipodevice' => null,
        // Agrega otros campos del dispositivo según sea necesario
    ];
    }
    });


        return response()->json($result);
    }

    // --------------------------------------------------------
    public function getUserDevices($userId)
    {
        // Obtener los dispositivos del usuario con los detalles del dispositivo y tipo de dispositivo
        $devicesUser = Inventory::join('device', 'device_user.device_id', '=', 'device.id')
            ->join('devicedetail', 'device.tipo_equipo_id', '=', 'devicedetail.id')
            ->where('device_user.user_id', $userId)
            ->where('device_user.enable', 1)
            ->select(
                'device_user.id as inventory_id',
                'device.id as id',
                'device.name as name',
                'device.user_id as user_id',
                'devicedetail.name as tipodevice'
            )
            ->get();

        return response()->json($devicesUser);
    }
// ------------------------------------------------
 // $devices = Device::whereIn('id', $devicesUser)->with('tipodevice')->get(); 
    // $devices = Device::where('user_id', $userId)->with('tipodevice')->get(); // Obtén los dispositivos asignados al usuario
    public function getUsers()
    {
        $users = User::all(); // Obtén todos los usuarios
        return response()->json($users);
    }
    public function verifyUserEmail($userId)
    {
        $user = User::find($userId);

        if ($user) {
            $user->email_verified_at = now();
            $user->save();

            return redirect()->back()->with('success', 'El correo electrónico del usuario ha sido verificado.');
        }

        return redirect()->back()->with('error', 'Usuario no encontrado.');
    }
    
    public function index(){
        
        $users = User::with('department')->get();
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
        // $enableforticket = Department::where('enableforticket',1)->pluck('name','id');
        $userDepartments = json_decode($user->ver_ticket, true) ?? [$user->department_id];
        return view('User.edit',
        [
            'user' => $user,
            // 'enableforticket' => $enableforticket,
            'userDepartments' => $userDepartments,
            'departments' => Department::pluck('name','id'),
            'sucursal' => Sucursal::pluck('name','id'),
        ]);
    }
    

    public function getUserDetails($userId){
        $user = User::with('department','sucursal')->find($userId);        
        return response()->json($user);
    }
    public function show(User $user){
        $user = User::find($user);        
        return response()->json($user);
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
            'sucursal_id' => 'nullable|integer',
            'is_admin' => 'nullable|integer',
            'extension' => 'nullable|min:2',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Asegúrate de validar también la imagen
        ]);

        $user->fill($validatedData);  
        if($request->has('ver_ticket')){
            $user->ver_ticket = json_encode($request->input('ver_ticket'));
        }else{
            $user->ver_ticket = json_encode([$request->input('department_id')]);
        }     

        if ($request->file('image')) {
            if ($oldImage) {
                Storage::delete('images/user/'. $oldImage);
            }
            $imageName = time() . '-' . $request->image->getClientOriginalName();
            $request->file('image')->storeAs('images/user', $imageName);
            
            $user->image = $imageName;
        }
        // return $request;

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
