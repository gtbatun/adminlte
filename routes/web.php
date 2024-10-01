<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SucursalController;


use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ChartJSController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ManttoController;
use App\Http\Controllers\InventoryController;

//sin agregar en web
use App\Http\Controllers\NotificationController;
use Illuminate\Notifications\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// DB::listen(function($query){
//     var_dump($query->sql);
// });


// Route::get('/', function () {
//     return view('auth.login');
// });

Auth::routes(['register' => true, 'verify' => true]);

Route::middleware(['auth','verified'])->group(function () {

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    Route::middleware('can:admin-access')->group(function(){
        Route::resource('area',AreaController::class);     

        Route::resource('department',DepartmentController::class);
        Route::resource('status',StatusController::class); 
        Route::resource('category',CategoryController::class);
        Route::resource('sucursal',SucursalController::class);
        
        //Ruta para el reset de contraseña de los usuarios
        Route::post('/user/update/password', [UserController::class, 'updatePassword'])->name('user.update.password');
        

        Route::get('setting', [SettingController::class,'index'])->name('setting.index');
        /**seccion para autorizar la verificacion de correo */
        Route::get('/admin/verify-email/{userId}', [UserController::class, 'verifyUserEmail'])->name('admin.verify-email');
        
        });

        //Ruta para crear vista index de reporte de tickets 
        Route::get('/reportes', [ReportController::class,'index'])->name('report.index')->middleware('can:access-report');
        //Genera la previsualizacion de tickets a exportar segun las fechas 
        Route::post('/reportes/generar', [ReportController::class,'generar'])->name('reportes.generar');
        // Route::get('/report/search', [ReportController::class, 'search2'])->name('report.search'); ///**** */
        //Exporta a un documento excel los tickets seleccionados
        Route::get('report-export/{fechaInicio}/{fechaFin}', [ReportController::class, 'reportexport'])->name('report-export');
        // Route::get('reporte-excel/{start_date}/{end_date}', [ReportController::class, 'reportexcel'])->name('reporte.excel');
        
// ruta agregad para visualizar las imagenes sin el link en cpanel
Route::get('storage/{archivo}', function ($archivo) {
    $rutaArchivo = storage_path('app/public/' . $archivo);
    if (!file_exists($rutaArchivo)) {
        abort(404);
    }
    $archivoMimeType = mime_content_type($rutaArchivo);
    return response()->file($rutaArchivo, ['Content-Type' => $archivoMimeType]);
    })->where('archivo', '.*')->name('archivo');


//Ruta GRUD para los usuarios
Route::resource('user', UserController::class);
Route::resource('gestion',GestionController::class);
Route::resource('ticket',TicketController::class);
Route::resource('device',DeviceController::class);
Route::resource('inventory',InventoryController::class)->middleware('can:access-inventory');



Route::get('/tickets/data', [TicketController::class, 'data'])->name('tickets.data');
/** Consultar las gestiones de cada ticket */
Route::get('/tickets/{ticket}/gestiones', [TicketController::class, 'getGestiones'])->name('tickets.gestiones');



                /**-------------------------------------------**/
                /**              -- Crear ticket --           **/
                /**-------------------------------------------**/
/** Consultar category asignada a un area y que pertenecen a un departamento */
Route::get('/get-area/{department_id}', [DepartmentController::class, 'getArea']);
Route::get('/get-category/{area_id}', [DepartmentController::class, 'getCategory']);

                /**-------------------------------------------**/
                /**                Editar de tickets          **/
                /**-------------------------------------------**/
/** Esta seccion funciona con la seleccion de l departmanto, se filtra las areas y los departamentos que contiene cada dep */
// Route::get('/areas/{departmento}', [TicketController::class, 'getAreas']);
// Route::get('/categorias/{area}', [TicketController::class, 'getCategorias']);

/** seccion de gestion
 * Ruta para editar el area y la categoria segun sea o decida el elcreador o al que se le asigno el ticket
 */
Route::get('getCategory',[TicketController::class,'getCategory'])->name('ticket.getCategory');
/**traer los departamento para el model de reasignacion de tickets */
Route::get('/departments/data', [DepartmentController::class, 'getDepartments'])->name('departments.data');

Route::post('/reasigticket', [TicketController::class, 'reasigticket'])->name('ticket.reasig');
/**Solicitar los departamentos segun sea la sucursal */
Route::get('/department/data/{sucursal_id}', [DepartmentController::class, 'getAllDepartments']);

/**ruta para ver el nuev index */
Route::get('/tickets/index2', [TicketController::class, 'index1'])->name('ticket.index1');
Route::get('/tickets/data2', [TicketController::class, 'data2'])->name('tickets.data2');

/**ver tickets cerrados */
Route::get('/ticket-closed',[TicketController::class,'closed'])->name('ticket.closed');


/** Consultar notificaciones de tickets */
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

//Exporta a un documento excel los tickets seleccionados
Route::get('/report/search', [ReportController::class, 'search'])->name('report.search'); ///**** */
Route::get('reporte-excel/{start_date}/{end_date}', [ReportController::class, 'reportexcel'])->name('reporte.excel');
Route::get('reporte-device/{start_date}/{end_date}', [ReportController::class, 'reportexcel_device'])->name('reporte.excel_device'); //****************

/**Ruta para nueva grafica */
/**solicitar los tickets creados o cerrado de un determinado mes, segun el mes seleccionado */
Route::get('/chart-per-month', [ChartJSController::class, 'getDataMonth'])->name('ticketsPerMonth'); /**Agente */
Route::get('/chart-per-month-department', [ChartJSController::class, 'getDepartmentDataMonth'])->name('ticketsDepartmentPerMonth'); /**Departamento */
Route::get('/chart-per-month-day', [ChartJSController::class, 'getDayDataMonth'])->name('ticketsDayPerMonth');/**Dia por dep */

/** Seccion inventario */
/**Solicitar todos los usuarios -- seccion asignar en los equipos */
Route::get('/users', [UserController::class, 'getUsers'])->name('users.list');
/**Solicitar los dispositivos que tienen asignado el usuario */
Route::get('/user/{id}/devices', [UserController::class, 'getUserDevices'])->name('user.devices');
// Route::get('/device-assignment/{id}/devices', [InventoryController::class, 'getUserDevices'])->name('device-assignment.devices');
/**asignar los equipos a los usuarios si es necesario */
Route::get('/assignments', [InventoryController::class, 'assignments'])->name('inventory.assignments');
/**Ruta la consulta de los devices */
Route::get('/api/devices', [DeviceController::class, 'getDevices'])->name('api.devices');

/** Ruta creada para la seccion de usuarios */
Route::get('/search-users', [UserController::class, 'searchUsers'])->name('user.searchUsers');
Route::get('/device-assignment/tipoequipo', [DeviceController::class, 'gettipoequipo'])->name('device-assignment.tipoequipo');
Route::get('/device-assignment/devices/{tipoequipoId}', [DeviceController::class, 'getDevicesByType'])->name('device-assignment.devices-by-type');
Route::post('/device-assignment/assign', [InventoryController::class, 'assignDevices'])->name('device-assignment.assign');
Route::get('/device-assignment/user-details/{userId}', [UserController::class, 'getUserDetails'])->name('device-assignment.user-details');

Route::post('/device-assignment/delete-device/{deviceId}', [InventoryController::class, 'deleteDevice']);

/** consultar los datos necesarios para poder crear un nuevo device */
Route::get('/device-data', [DeviceController::class, 'getDeviceData']);

/** Guardado del mantenimiento de los equipos*/
Route::resource('mantto',ManttoController::class);
/** Ruta para encontrar los estatus de los devices en devicesdetail */
Route::get('/statuses', [DeviceController::class, 'getStatuses']);
/**Solicitar a la tabla devicedetail los tasks */
Route::get('/tasks', [DeviceController::class, 'getTasks']);
/**Solicitar los tasks segun el device */ //  /device/{deviceId}/tasks   getTasksByDevice
Route::get('/device/{deviceId}/tasks-and-assignments', [ManttoController::class, 'getTasksAndAssignByDevice']);
/**Solicitar los movimientos del device */
// Route::get('/hist_device/{deviceId}', [InventoryController::class, 'getAssignByDevice']);


/** tickets por dia */
// Route::get('/chart-per-day', [ChartJSController::class, 'getDataDay'])->name('ticketsPerDay');
// /** tickets cerrados por personal de sistemas o agente*/
// Route::get('/chart-data', [ChartJSController::class, 'getChartData'])->name('chart.data');
// /** tickets por departamento */
// Route::get('/chart-by-department', [ChartJSController::class, 'getDatadepartment'])->name('ticketsByDepartment');
// /**nuevas graficas, las funciones estan en la doc chart3.js */
// Route::get('/data', [ChartJSController::class,'getData'])->name('chart.data');
// Route::get('/more-data',[ChartJSController::class,'getMoreData'])->name('chart.more-data');
// Route::get('/report/getData', [ChartJSController::class, 'getData'])->name('report.getData');

// Route::get('ticket-export/', [TicketController::class, 'export'])->name('ticket-export');

/**Ruta para solicitar lñainfo del tickets y ver el modal desde notificaciones */
Route::get('/tickets/{id}/details', [TicketController::class, 'getDetails'])->name('tickets.details');
Route::get('/notifications/count', [NotificationController::class, 'getUnreadNotificationsCount'])->name('notifications.count');

// ruta para generar los codigos de barra de los equipos ya dados de alta
Route::get('/generate-qrcodes', [DeviceController::class, 'generateQRCodes']);

//ruta para la desasignacion y asignacion masiva
// Route::post('/assign-devices', [InventoryController::class, 'assignDevicesMassive']);
Route::post('/unassign-devices', [InventoryController::class, 'unassignDevicesMassive']);
// Route::post('/reassign-devices', [InventoryController::class, 'markAsRead'])->name('notifications.markAsRead');

/** Consultar las notificaciones sin leer o nuevas (version antigua de la verificacion  de nuevos tickets) */
Route::get('/tickets/check-updates', [TicketController::class, 'checkUpdates'])->name('tickets.check-updates');


/**ruta para conmsultar las nuevas notificaciones y mostrar una alerta o recargar la tabla de datos */
Route::get('/tickets/check-new-notifications', [NotificationController::class, 'checkNewNotifications'])->name('tickets.check-new-notifications');

});

