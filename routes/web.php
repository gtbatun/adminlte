<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AreaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\GestionController;
use App\Http\Controllers\InventoryController;
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

use App\Http\Controllers\NotificationController;

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
        //Ruta para crear vista index de reporte de tickets 
        Route::get('/reportes', [ReportController::class,'index'])->name('report.index');
        //Genera la previsualizacion de tickets a exportar segun las fechas 
        Route::post('/reportes/generar', [ReportController::class,'generar'])->name('reportes.generar');
        Route::get('/report/search', [ReportController::class, 'search2'])->name('report.search'); ///**** */
        //Exporta a un documento excel los tickets seleccionados
        Route::get('report-export/{fechaInicio}/{fechaFin}', [ReportController::class, 'reportexport'])->name('report-export');
        Route::get('reporte-excel/{start_date}/{end_date}', [ReportController::class, 'reportexcel'])->name('reporte.excel');
        Route::get('setting', [SettingController::class,'index'])->name('setting.index');
        /**seccion para autorizar la verificacion de correo */
        Route::get('/admin/verify-email/{userId}', [UserController::class, 'verifyUserEmail'])->name('admin.verify-email');
        
        });

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
Route::get('ticket/getCategory',[TicketController::class,'getCategory'])->name('ticket.getCategory');
Route::resource('gestion',GestionController::class);
Route::resource('ticket',TicketController::class);

Route::resource('inventory',InventoryController::class);

Route::get('/tickets/data', [TicketController::class, 'data'])->name('tickets.data');
/** Consultar las gestiones de cada ticket */
Route::get('/tickets/{ticket}/gestiones', [TicketController::class, 'getGestiones'])->name('tickets.gestiones');

/**Consultar category asignada a un area y que pertenecen a un departamento */
Route::get('/get-area/{department_id}', [DepartmentController::class, 'getArea']);
Route::get('/get-category/{area_id}', [DepartmentController::class, 'getCategory']);

/**ver tickets cerrados */
Route::get('/ticket-closed',[TicketController::class,'closed'])->name('ticket.closed');
/** Consultar los tickets cerrados */
Route::get('/tickets/check-updates', [TicketController::class, 'checkUpdates'])->name('tickets.check-updates');
/** Consultar notificaciones de tickets */
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

Route::get('graf/', [ChartJSController::class, 'index']); 
Route::post('graf/store',[ ChartJSController::class, 'store'])->name('reportessssss.store');
/**nuevas graficas */
Route::get('/data', [ChartJSController::class,'getData'])->name('chart.data');
Route::get('/more-data',[ChartJSController::class,'getMoreData'])->name('chart.more-data');
Route::get('/report/getData', [ChartJSController::class, 'getData'])->name('report.getData');

Route::get('ticket-export/', [TicketController::class, 'export'])->name('ticket-export');





});

