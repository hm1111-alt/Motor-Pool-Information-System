<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\CalendarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Travel Order Routes
    Route::get('/travel-orders', [TravelOrderController::class, 'index'])->name('travel-orders.index');
    Route::get('/travel-orders/create', [TravelOrderController::class, 'create'])->name('travel-orders.create');
    Route::post('/travel-orders', [TravelOrderController::class, 'store'])->name('travel-orders.store');
    Route::get('/travel-orders/{travelOrder}/edit', [TravelOrderController::class, 'edit'])->name('travel-orders.edit');
    Route::put('/travel-orders/{travelOrder}', [TravelOrderController::class, 'update'])->name('travel-orders.update');
    Route::delete('/travel-orders/{travelOrder}', [TravelOrderController::class, 'destroy'])->name('travel-orders.destroy');
    
    // Calendar Routes
    Route::get('/vehicle-calendar', [CalendarController::class, 'index'])->name('vehicle-calendar.index');
    Route::get('/vehicle-calendar/events', [CalendarController::class, 'getEvents'])->name('vehicle-calendar.events');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';