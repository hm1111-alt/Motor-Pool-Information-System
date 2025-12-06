<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TravelOrderController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SubunitController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaderController;
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
    Route::get('/travel-orders/{travelOrder}', [TravelOrderController::class, 'show'])->name('travel-orders.show');
    Route::put('/travel-orders/{travelOrder}/approve', [TravelOrderController::class, 'approve'])->name('travel-orders.approve');
    
    // Approved Travel Orders for Motorpool Admin
    Route::get('/approved-travel-orders', [DashboardController::class, 'approvedTravelOrders'])->name('approved-travel-orders.index');
    
    // Admin Routes
    // Organization Structure Management
    Route::get('/admin/offices', [OfficeController::class, 'index'])->name('admin.offices.index');
    Route::get('/admin/offices/create', [OfficeController::class, 'create'])->name('admin.offices.create');
    Route::post('/admin/offices', [OfficeController::class, 'store'])->name('admin.offices.store');
    Route::get('/admin/offices/{office}/edit', [OfficeController::class, 'edit'])->name('admin.offices.edit');
    Route::put('/admin/offices/{office}', [OfficeController::class, 'update'])->name('admin.offices.update');
    Route::delete('/admin/offices/{office}', [OfficeController::class, 'destroy'])->name('admin.offices.destroy');
    
    Route::get('/admin/divisions', [DivisionController::class, 'index'])->name('admin.divisions.index');
    Route::get('/admin/divisions/create', [DivisionController::class, 'create'])->name('admin.divisions.create');
    Route::post('/admin/divisions', [DivisionController::class, 'store'])->name('admin.divisions.store');
    Route::get('/admin/divisions/{division}/edit', [DivisionController::class, 'edit'])->name('admin.divisions.edit');
    Route::put('/admin/divisions/{division}', [DivisionController::class, 'update'])->name('admin.divisions.update');
    Route::delete('/admin/divisions/{division}', [DivisionController::class, 'destroy'])->name('admin.divisions.destroy');
    
    Route::get('/admin/units', [UnitController::class, 'index'])->name('admin.units.index');
    Route::get('/admin/units/create', [UnitController::class, 'create'])->name('admin.units.create');
    Route::post('/admin/units', [UnitController::class, 'store'])->name('admin.units.store');
    Route::get('/admin/units/{unit}/edit', [UnitController::class, 'edit'])->name('admin.units.edit');
    Route::put('/admin/units/{unit}', [UnitController::class, 'update'])->name('admin.units.update');
    Route::delete('/admin/units/{unit}', [UnitController::class, 'destroy'])->name('admin.units.destroy');
    
    Route::get('/admin/subunits', [SubunitController::class, 'index'])->name('admin.subunits.index');
    Route::get('/admin/subunits/create', [SubunitController::class, 'create'])->name('admin.subunits.create');
    Route::post('/admin/subunits', [SubunitController::class, 'store'])->name('admin.subunits.store');
    Route::get('/admin/subunits/{subunit}/edit', [SubunitController::class, 'edit'])->name('admin.subunits.edit');
    Route::put('/admin/subunits/{subunit}', [SubunitController::class, 'update'])->name('admin.subunits.update');
    Route::delete('/admin/subunits/{subunit}', [SubunitController::class, 'destroy'])->name('admin.subunits.destroy');
    
    Route::get('/admin/classes', [ClassController::class, 'index'])->name('admin.classes.index');
    Route::get('/admin/classes/create', [ClassController::class, 'create'])->name('admin.classes.create');
    Route::post('/admin/classes', [ClassController::class, 'store'])->name('admin.classes.store');
    Route::get('/admin/classes/{class}/edit', [ClassController::class, 'edit'])->name('admin.classes.edit');
    Route::put('/admin/classes/{class}', [ClassController::class, 'update'])->name('admin.classes.update');
    Route::delete('/admin/classes/{class}', [ClassController::class, 'destroy'])->name('admin.classes.destroy');
    
    Route::get('/admin/employees', [EmployeeController::class, 'index'])->name('admin.employees.index');
    Route::get('/admin/employees/create', [EmployeeController::class, 'create'])->name('admin.employees.create');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/admin/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/admin/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');
    
    // AJAX routes for employee form dependencies
    Route::get('/admin/employees/get-divisions-by-office', [EmployeeController::class, 'getDivisionsByOffice'])->name('admin.employees.get-divisions-by-office');
    Route::get('/admin/employees/get-units-by-division', [EmployeeController::class, 'getUnitsByDivision'])->name('admin.employees.get-units-by-division');
    Route::get('/admin/employees/get-subunits-by-unit', [EmployeeController::class, 'getSubunitsByUnit'])->name('admin.employees.get-subunits-by-unit');
    
    // Leadership Routes
    Route::get('/admin/leaders', [LeaderController::class, 'index'])->name('admin.leaders.index');
    Route::get('/admin/leaders/edit/{type}/{id?}', [LeaderController::class, 'edit'])->name('admin.leaders.edit');
    Route::post('/admin/leaders/update', [LeaderController::class, 'update'])->name('admin.leaders.update');
    
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