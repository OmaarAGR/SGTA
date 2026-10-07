<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Client\ClientAppointmentController;
use App\Http\Controllers\Client\ClientVehicleController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Controllers\TechnicianController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', fn () =>
    Auth::check()
        ? to_route('dashboard')
        : to_route('login')
)->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clients', ClientController::class);
    Route::resource('clients.appointments', ClientAppointmentController::class)->shallow();
    Route::resource('clients.vehicles', ClientVehicleController::class)->shallow();

    Route::resource('technicians', TechnicianController::class);
    Route::resource('technicians.appointments', ClientAppointmentController::class);
    Route::resource('technicians.service_records', ClientAppointmentController::class);

    Route::resource('vehicles', VehicleController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('service_records', ServiceRecordController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
