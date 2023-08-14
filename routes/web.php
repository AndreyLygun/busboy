<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\VisitController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::domain('{id}.busboy.test')->group(function () {
    Route::get('/', [VisitController::class, 'info'])->middleware('getCompanyId');
    Route::get('/menu', [VisitController::class, 'menu'])->middleware('getCompanyId');
    Route::get('/cart', [VisitController::class, 'cart'])->middleware('getCompanyId');
    Route::get('/waiter', [VisitController::class, 'waiter'])->middleware('getCompanyId');
}) ;


Route::controller(ImportExportController::class)->group(function() {
    Route::get('/admin/exportmenu', 'ExportMenu')->name('exportmenu');
});



Route::get('/', function () {
    return view('welcome');
});



