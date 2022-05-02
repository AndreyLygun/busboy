<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;
use App\Http\Controllers\CabinetController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\AuthController;

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

Route::domain('demo.busboy.test')->group(function() {
    Route::get('/', [VisitController::class, 'about'])->name('visit.about');
    Route::get('/menu.html', [VisitController::class, 'menu'])->name('visit.menu');
    Route::get('/cart.html', [VisitController::class, 'cart'])->name('visit.cart');
    Route::get('/waiter.html', [VisitController::class, 'waiter'])->name('visit.waiter');
    Route::get('/carthandler.html', [VisitController::class, 'cartHandler'])->name('visit.carthandler');
    Route::get('/sendOrder.html', [VisitController::class, 'sendOrder'])->name('visit.sendOrder');
});




//Route::domain('demo.busboy.test')->get('/', [VisitController::class, 'about'])->name('visit.about');
//Route::domain('demo.busboy.test')->get('/menu.html', [VisitController::class, 'menu'])->name('visit.menu');
//Route::domain('demo.busboy.test')->get('/cart.html', [VisitController::class, 'cart'])->name('visit.cart');
//Route::domain('demo.busboy.test')->get('/waiter.html', [VisitController::class, 'waiter'])->name('visit.waiter');
//
//Route::domain('demo.busboy.test')->get('/carthandler.html', [VisitController::class, 'cartHandler'])->name('visit.carthandler');
//Route::domain('demo.busboy.test')->get('/sendOrder.html', [VisitController::class, 'sendOrder'])->name('visit.sendOrder');
//
//Route::domain('demo.busboy.test')->get('/ses.html', function() {
//    dump(session('cart'));
//    dump(session('ordered'));
////    session()->flush();
//});


Route::post('/import/', function () {
    Excel::import(new \App\Imports\DishImport(), request()->file('excel'));
});


// Промо-часть
Route::get('/', function () {return view('promo.home');})->name('home');
Route::get('/about',  function () {return view('promo.about');})->name('about');
Route::get('/price', function () {return view('promo.price');})->name('price');
Route::get('/contacts', function () {return view('promo.contacts');})->name('contacts');

// Регистрация и ввторизация
Route::get('register', [AuthController::class, 'registerUser'])->name('registerUser')->middleware('guest');
Route::post('register', [AuthController::class, 'storeUser'])->middleware('guest');
Route::post('login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::get('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Кабинет пользователя
Route::get('/cabinet/settings', [CabinetController::class, 'getSettings'])->name('cabinet.getSettings')->middleware(['auth']);
Route::post('/cabinet/settings', [CabinetController::class, 'saveSettings'])->name('cabinet.saveSettings')->middleware(['auth']);
Route::redirect('/cabinet', '/cabinet/settings')->name('cabinet')->middleware(['auth']);
Route::get('/cabinet/menu', [CabinetController::class, 'menu'])->name('cabinet.menu')->middleware(['auth']);
Route::get('/cabinet/places', [CabinetController::class, 'places'])->name('cabinet.places')->middleware(['auth']);
Route::get('/cabinet/staff', [CabinetController::class, 'staff'])->name('cabinet.staff')->middleware(['auth']);

// Меню в кабинете пользователя
Route::post('/cabinet/menu/import', [MenuController::class, 'importMenu'])->name('menu.import')->middleware(['auth']);
Route::get('/cabinet/menu/export', [MenuController::class, 'exportMenu'])->name('menu.export')->middleware(['auth']);
Route::get('/cabinet/menu/adddish', [MenuController::class, 'addDish'])->name('menu.adddish')->middleware(['auth']);
Route::get('cabinet/menu/edit/{dish}', [MenuController::class, 'editDish'])->name('menu.editdish')->middleware(['auth']);
Route::post('cabinet/menu/edit/{dish}', [MenuController::class, 'updateDish'])->name('menu.updatedish')->middleware(['auth']);
Route::post('/cabinet/menu/addcategory', [MenuController::class, 'addCategory'])->name('menu.addcategory')->middleware(['auth']);
Route::post('/cabinet/menu/changeorder', [MenuController::class, 'changeOrder'])->name('menu.changeorder')->middleware(['auth']);
Route::post('/cabinet/menu/delete', [MenuController::class, 'deleteDishes'])->name('menu.deletedishes')->middleware(['auth']);
