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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('area',AreaController::class);
Route::resource('category',CategoryController::class);
Route::resource('department',DepartmentController::class);
Route::resource('gestion',GestionController::class);
Route::resource('inventory',InventoryController::class);
Route::resource('status',StatusController::class);
Route::resource('ticket',TicketController::class);

Route::get('ticket-export/', [TicketController::class, 'export']);

// Route::get('graf', [ChartJSController::class, 'index']);
// Route::get('/graf',[ ChartJSController::class, 'ticketsChart'])->name('tickets.chart');
// Route::get('/tickets-data', [ChartJSController::class, 'ticketsData'])->name('tickets.data');

});

