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

use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ChartJSController;

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


Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::middleware('auth')->group(function () {

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    Route::middleware('can:admin-access')->group(function(){
        Route::resource('area',AreaController::class);
        Route::resource('category',CategoryController::class);
        Route::resource('department',DepartmentController::class);
        Route::resource('inventory',InventoryController::class);
        Route::resource('status',StatusController::class); 
        
        
        //Ruta para el reset de contraseña de los usuarios
        Route::post('/user/update/password', [UserController::class, 'updatePassword'])->name('user.update.password');
        //Ruta para crear vista index de reporte de tickets 
        Route::get('/reportes', [ReportController::class,'index'])->name('report.index');
        //Genera la previsualizacion de tickets a exportar segun las fechas 
        Route::post('/reportes/generar', [ReportController::class,'generar'])->name('reportes.generar');
        //Exporta a un documento excel los tickets seleccionados
        Route::get('report-export/{fechaInicio}/{fechaFin}', [ReportController::class, 'reportexport'])->name('report-export');
    });
//Ruta GRUD para los usuarios
Route::resource('user', UserController::class);

Route::resource('gestion',GestionController::class);
Route::resource('ticket',TicketController::class);





Route::get('graf/', [ChartJSController::class, 'index']); 

// Route::get('graf1', [ChartJSController::class, 'index']); 

Route::post('graf/store',[ ChartJSController::class, 'store'])->name('reportessssss.store');
// Route::get('/tickets-data', [ChartJSController::class, 'ticketsData'])->name('tickets.data');

// Route::get('ticket-report',[TicketController::class,'showReport'])->name('ticket.report');



Route::get('ticket-export/', [TicketController::class, 'export'])->name('ticket-export');

// Route::get('report-export/', [ReportController::class, 'reportexport'])->name('report-export');

// Route::get('ticket-export/', [TicketController::class, 'export']);
// Route::get('ticket-export/{fechaInicio}/{fechaFin}', [TicketController::class, 'export'])->name('ticket-export');

});

