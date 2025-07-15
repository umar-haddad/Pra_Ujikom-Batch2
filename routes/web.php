<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LaundryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransOrderController;
use App\Http\Controllers\TransOrderPaymentController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get("/", [LoginController::class, 'login']);
Route::get("login", [LoginController::class, 'login'])->name('login');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('actionLogin',  [LoginController::class, 'actionLogin'])->name('actionLogin');
Route::resource('dashboard', DashboardController::class);
Route::get('error404', [DashboardController::class, 'showErrorPage'])->name('error404');



Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.index');
    Route::resource('level', LevelController::class);
    Route::resource('service', ServiceController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('user', UserController::class);
    Route::resource('trans', TransOrderController::class);
    Route::get('/trans/{id}/print-struk', [TransOrderPaymentController::class, 'printStruk'])->name('print_struk');


    Route::post('trans/{id}/snap', [TransOrderPaymentController::class, 'snap'])->name('trans.snap');
    Route::get('trans/laundry', [TransOrderController::class, 'createLaundry'])->name('trans.createLaundry');
    // Route::post('/transactions', [TransOrderPaymentController::class, 'apiTransactions']);
});
