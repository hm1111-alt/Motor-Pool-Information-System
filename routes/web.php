<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\OfficeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
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
    
    // Admin Routes
    // Organization Structure Management
    Route::get('/admin/offices', [OfficeController::class, 'index'])->name('admin.offices.index');
    Route::get('/admin/offices/create', [OfficeController::class, 'create'])->name('admin.offices.create');
    Route::post('/admin/offices', [OfficeController::class, 'store'])->name('admin.offices.store');
    Route::get('/admin/offices/{office}/edit', [OfficeController::class, 'edit'])->name('admin.offices.edit');
    Route::put('/admin/offices/{office}', [OfficeController::class, 'update'])->name('admin.offices.update');
    Route::delete('/admin/offices/{office}', [OfficeController::class, 'destroy'])->name('admin.offices.destroy');
    
    Route::get('/admin/divisions', function () {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return view('admin.divisions.index');
    })->name('admin.divisions.index');
    
    Route::get('/admin/units', function () {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return view('admin.units.index');
    })->name('admin.units.index');
    
    Route::get('/admin/subunits', function () {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return view('admin.subunits.index');
    })->name('admin.subunits.index');
    
    Route::get('/admin/classes', function () {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return view('admin.classes.index');
    })->name('admin.classes.index');
    
    Route::get('/admin/employees', function () {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return view('admin.employees.index');
    })->name('admin.employees.index');
    
    Route::get('/admin/leaders', function () {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard');
        }
        return view('admin.leaders.index');
    })->name('admin.leaders.index');
    
    // Calendar Routes
    Route::get('/vehicle-calendar', [CalendarController::class, 'index'])->name('vehicle-calendar.index');
    Route::get('/vehicle-calendar/events', [CalendarController::class, 'getEvents'])->name('vehicle-calendar.events');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';