<?php

namespace App\Http\Controllers;
use App\Models\Device;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Sucursal;
use App\Models\Devicedetail;
use App\Models\Inventory;
use App\Models\User;

/** para generar los codigos qr*/
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;


use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode;

class DeviceController extends Controller
{
    public function generateQRCodes()
    {
        // Obtener todos los dispositivos sin código QR
        $devices = Device::whereNull('qr_code')->get();

        foreach ($devices as $device) {
            // Generar el código QR basado en la URL del equipo
            // $qrCode = Builder::create()
            //     ->data(route('devices.show', $device->id))
            //     ->size(300)
            //     ->margin(10)
            //     ->build();

            // Convertir el código QR a Base64
            // $qrCodeBase64 = base64_encode($qrCode->getString());

            // Guardar el código QR en la base de datos
            // $device->qr_code_base64 = $qrCodeBase64;
            // Generar el código QR
            $qrCode = new QrCode($device->name);
            $qrCode->setSize(200);
            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            // Codificar la imagen QR en Base64
            $device->qr_code = base64_encode($result->getString());
            $device->save();
        }

        return redirect()->back()->with('success', 'Códigos QR generados exitosamente.');
    }
    public function getDeviceData()
    {
        $tipo_equipo = Devicedetail::where('type_device', 1)->pluck('name', 'id');
        $marca = Devicedetail::where('type_device', 2)->pluck('name', 'id');
        $almacenamiento = Devicedetail::where('type_device', 3)->pluck('name', 'id');
        $procesador = Devicedetail::where('type_device', 4)->pluck('name', 'id');
        $status = Devicedetail::where('type_device', 5)->pluck('name', 'id');
        $department = Department::pluck('name', 'id');
        $sucursal = Sucursal::pluck('name', 'id');
        

        return response()->json(compact('tipo_equipo', 'marca', 'almacenamiento', 'procesador', 'status', 'department', 'sucursal'));
    }
    public function getTasks()
    {
        $tasks = Devicedetail::where('type_device',6)->get();
        return response()->json($tasks);
    }
    public function getStatuses()
    {
        $statuses = Devicedetail::where('type_device',5)->get();
        return response()->json($statuses);
    }
    public function getDevicesByType($tipoequipoId)
    {
        $devices = Device::where('tipo_equipo_id', $tipoequipoId)
        ->where('statusdevice_id',15)
        ->with('tipodevice') // Cargar la relación tipodevice
        ->get();
        // $devices = Device::all();
        return response()->json($devices);
    }
    public function gettipoequipo(){
        $categories = Devicedetail::where('type_device',1)->get();
        return response()->json($categories);
    }
    public function getDevices()
    {
        // Obtener todos los dispositivos con sus relaciones necesarias
        $devices = Device::with(['usuario','marca','tipodevice','statusdevice','sucursal','departamento'])->get();

        return response()->json($devices);
    }
    /**
     * Display a listing of the resource.
      */
    //    'usuario','marca','tipodevice','statusdevice','sucursal','departamento'
    public function index()
    {
        $devices = Device::latest()->with('usuario','marca','tipodevice','statusdevice','sucursal','departamento')->get();
        return view('Device.index',['devices' => $devices]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipo_equipo = Devicedetail::where('type_device',1)->pluck('name','id');
        $marca = Devicedetail::where('type_device',2)->pluck('name','id');
        $almacenamiento = Devicedetail::where('type_device',3)->pluck('name','id');
        $procesador = Devicedetail::where('type_device',4)->pluck('name','id');
        $status = Devicedetail::where('type_device',5)->pluck('name','id');
        $equipo = new Device;
        $department = Department::pluck('name','id');
        $sucursal = Sucursal::pluck('name','id');
        return view('Device.create',compact('department','equipo','sucursal','tipo_equipo','marca','almacenamiento','procesador','status'));
        // return response()->json(compact('tipo_equipo', 'marca', 'almacenamiento', 'procesador', 'status', 'department', 'sucursal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validar los datos
         $validatedData = $request->validate([
            'name' => 'required',
            'tipo_equipo_id' => 'required',
            'marca_id' => 'required',
            'almacenamiento_id' => 'required',
            'procesador_id' => 'required',
            'statusdevice_id' => 'required',
            'sucursal_id' => 'required',
            // Agrega más validaciones según sea necesario
        ]);        

        // Crear el nuevo dispositivo
        $device = new Device($validatedData);  // Asignar datos directamente desde el arreglo validado
        $device->serie = $request->serie;
        $device->description = $request->description;

        // Guarda más campos según sea necesario
        if(isset($request->user_id)){
            $device->user_id = $request->user_id;
            $user = User::find($request->user_id);
            $device->department_id = $user->department_id;   
        }
        $device->save();

        // Ahora puedes obtener el ID del dispositivo recién creado
        $deviceId = $device->id;

        $qrCode = new QrCode($deviceId);        
        $qrCode->setSize(200);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Codificar la imagen QR en Base64 y actualizar el dispositivo con el código QR
        $device->qr_code = base64_encode($result->getString());

        $device->save();  // Guardar el dispositivo nuevamente con el código QR


        if(isset($request->user_id)){
            $inventory = new Inventory();
            $inventory->device_id = $deviceId; // Aquí obtienes el ID del dispositivo recién creado
            $inventory->user_id = $request->user_id;
            $inventory->coment = $request->coment;
            $inventory->tipo = 'entrega';
            $inventory->enable = 1;
            $inventory->save();
        }

        // Retornar una respuesta JSON
        return response()->json(['success' => true, 'message' => 'Dispositivo creado correctamente']);

    }

    public function store2(Request $request)
    {
         // Validar los datos
         $request->validate([
            'name' => 'required',
            'tipo_equipo_id' => 'required',
            'marca_id' => 'required',
            'almacenamiento_id' => 'required',
            'procesador_id' => 'required',
            'statusdevice_id' => 'required',
            'sucursal_id' => 'required',
            // Agrega más validaciones según sea necesario
        ]);        

        // Crear el nuevo dispositivo
        $device = new Device;
        $device->name = $request->name;
        $device->serie = $request->serie;
        $device->description = $request->description;
        $device->tipo_equipo_id = $request->tipo_equipo_id;
        $device->marca_id = $request->marca_id;
        $device->almacenamiento_id = $request->almacenamiento_id;
        $device->procesador_id = $request->procesador_id;
        $device->statusdevice_id = $request->statusdevice_id;
        $device->sucursal_id = $request->sucursal_id;
        // Guarda más campos según sea necesario
        if(isset($request->user_id)){
            $device->user_id = $request->user_id;
            $user = User::find($request->user_id);
            $device->department_id = $user->department_id;   
        }

        // Generar el código QR como Base64
            // $qrCode = Builder::create()
            // ->data(route('device.show', $device->id)) // URL para buscar el equipo
            // ->size(300)
            // ->margin(10)
            // ->build();

        // Generar el código QR
        // $qrCode = new QrCode($request->name);
        $qrCode = new QrCode($request->name);
        // $qrCode = new QrCode($device->id);
        
        $qrCode->setSize(200);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Codificar la imagen QR en Base64
        $device->qr_code = base64_encode($result->getString());

        //generar el codigo-qr
        // $qrCodeBase64 = base64_encode($qrCode->getString());
        // $device->qr_code = $qrCodeBase64;

        $device->save();
        // Ahora puedes obtener el ID del dispositivo recién creado
        $deviceId = $device->id;

        if(isset($request->user_id)){
            $inventory = new Inventory();
            $inventory->device_id = $deviceId; // Aquí obtienes el ID del dispositivo recién creado
            $inventory->user_id = $request->user_id;
            $inventory->coment = $request->coment;
            $inventory->tipo = 'entrega';
            $inventory->enable = 1;
            $inventory->save();
        }

        // Retornar una respuesta JSON
        return response()->json(['success' => true, 'message' => 'Dispositivo creado correctamente']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        return view('Device.edit',[
            'equipo' => $device,
            'tipo_equipo' => Devicedetail::where('type_device',1)->pluck('name','id'),
            'marca' => Devicedetail::where('type_device',2)->pluck('name','id'),
            'almacenamiento' => Devicedetail::where('type_device',3)->pluck('name','id'),
            'procesador' => Devicedetail::where('type_device',4)->pluck('name','id'),
            'status' => Devicedetail::where('type_device',5)->pluck('name','id'),
            'department' => Department::pluck('name','id'),
           'sucursal' => Sucursal::pluck('name','id')
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Device $device)
    {
        // return $request;
        $device->update($request->all()); 
        return redirect()->route('device.index')->with('success','El equipoo fue actualizado con exito');

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        $device->delete();
        // return redirect()->route('inventory.index')->with('success', 'dispositivo Eliminado exitosamente');
        return response()->json(['success' => 'Dispositivo eliminado correctamente.']);
    }
}
