<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\SubunitController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaderController;
use App\Http\Controllers\RegularEmployeeTravelOrderController;
use App\Http\Controllers\HeadTravelOrderController;
use App\Http\Controllers\HeadOwnTravelOrderController;
use App\Http\Controllers\DivisionHeadTravelOrderController;
use App\Http\Controllers\DivisionHeadOwnTravelOrderController;
use App\Http\Controllers\UnitHeadOwnTravelOrderController;
use App\Http\Controllers\VpTravelOrderController;
use App\Http\Controllers\VpOwnTravelOrderController;
use App\Http\Controllers\PresidentTravelOrderController;
use App\Http\Controllers\PresidentOwnTravelOrderController;
use App\Http\Controllers\MotorpoolAdminController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Test route for vehicle add button
Route::get('/vehicles/test-add-button', function () {
    return view('vehicles.test-add-button');
})->name('vehicles.test-add-button');

// Simple vehicle index route
Route::get('/vehicles/simple-index', [VehicleController::class, 'simpleIndex'])->name('vehicles.simple-index');

// Direct test route for add vehicle button
Route::get('/test/add-vehicle-button', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head>
        <title>Add Vehicle Test</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .btn { display: inline-block; padding: 12px 24px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; }
            .btn:hover { background-color: #218838; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Vehicle Management Test</h1>
            <p>Click the button below to add a new vehicle:</p>
            <a href="' . route('vehicles.create') . '" class="btn">+ Add New Vehicle</a>
            <p style="margin-top: 20px;"><a href="' . url('/') . '">← Back to Home</a></p>
        </div>
    </body>
    </html>';
})->name('test.add-vehicle-button');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
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
    Route::get('/admin/employees/test-form', function () {
        return view('admin.employees.test-form');
    })->name('admin.employees.test-form');
    Route::get('/admin/employees/debug-form', function () {
        return view('admin.employees.debug-form');
    })->name('admin.employees.debug-form');
    Route::get('/admin/employees/create-fixed', function () {
        $offices = \App\Models\Office::all();
        $classes = \App\Models\ClassModel::all();
        $divisions = \App\Models\Division::all();
        $units = \App\Models\Unit::all();
        $subunits = \App\Models\Subunit::all();
        $roles = collect([
            (object)['id' => 0, 'name' => 'none'],
            (object)['id' => 1, 'name' => 'unit_head'],
            (object)['id' => 2, 'name' => 'division_head'],
            (object)['id' => 3, 'name' => 'vp'],
            (object)['id' => 4, 'name' => 'president'],
        ]);
        return view('admin.employees.create-fixed', compact('offices', 'classes', 'divisions', 'units', 'subunits', 'roles'));
    })->name('admin.employees.create-fixed');
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
    
    // Travel Order Routes for Regular Employees
    Route::get('/travel-orders', [RegularEmployeeTravelOrderController::class, 'index'])->name('travel-orders.index');
    Route::get('/travel-orders/create', [RegularEmployeeTravelOrderController::class, 'create'])->name('travel-orders.create');
    Route::post('/travel-orders', [RegularEmployeeTravelOrderController::class, 'store'])->name('travel-orders.store');
    Route::get('/travel-orders/{travelOrder}', [RegularEmployeeTravelOrderController::class, 'show'])->name('travel-orders.show');
    Route::get('/travel-orders/{travelOrder}/edit', [RegularEmployeeTravelOrderController::class, 'edit'])->name('travel-orders.edit');
    Route::put('/travel-orders/{travelOrder}', [RegularEmployeeTravelOrderController::class, 'update'])->name('travel-orders.update');
    Route::delete('/travel-orders/{travelOrder}', [RegularEmployeeTravelOrderController::class, 'destroy'])->name('travel-orders.destroy');
    
    // Travel Order Routes for Heads (own travel orders)
    Route::get('/head/travel-orders', [HeadOwnTravelOrderController::class, 'index'])->name('head.travel-orders.index');
    Route::get('/head/travel-orders/create', [HeadOwnTravelOrderController::class, 'create'])->name('head.travel-orders.create');
    Route::post('/head/travel-orders', [HeadOwnTravelOrderController::class, 'store'])->name('head.travel-orders.store');
    Route::get('/head/travel-orders/{travelOrder}', [HeadOwnTravelOrderController::class, 'show'])->name('head.travel-orders.show');
    Route::get('/head/travel-orders/{travelOrder}/edit', [HeadOwnTravelOrderController::class, 'edit'])->name('head.travel-orders.edit');
    Route::put('/head/travel-orders/{travelOrder}', [HeadOwnTravelOrderController::class, 'update'])->name('head.travel-orders.update');
    Route::delete('/head/travel-orders/{travelOrder}', [HeadOwnTravelOrderController::class, 'destroy'])->name('head.travel-orders.destroy');
    
    // Travel Order Approval Routes for Heads
    Route::get('/travel-orders/approvals/head', [HeadTravelOrderController::class, 'index'])->name('travel-orders.approvals.head');
    Route::get('/travel-orders/{travelOrder}/approval-show/head', [HeadTravelOrderController::class, 'show'])->name('travel-orders.approval-show.head');
    Route::put('/travel-orders/{travelOrder}/approve/head', [HeadTravelOrderController::class, 'approve'])->name('travel-orders.approve.head');
    Route::put('/travel-orders/{travelOrder}/reject/head', [HeadTravelOrderController::class, 'reject'])->name('travel-orders.reject.head');
    
    // Travel Order Routes for Unit Heads (own travel orders)
    Route::get('/unithead/travel-orders', [UnitHeadOwnTravelOrderController::class, 'index'])->name('unithead.travel-orders.index');
    Route::get('/unithead/travel-orders/create', [UnitHeadOwnTravelOrderController::class, 'create'])->name('unithead.travel-orders.create');
    Route::post('/unithead/travel-orders', [UnitHeadOwnTravelOrderController::class, 'store'])->name('unithead.travel-orders.store');
    Route::get('/unithead/travel-orders/{travelOrder}', [UnitHeadOwnTravelOrderController::class, 'show'])->name('unithead.travel-orders.show');
    Route::get('/unithead/travel-orders/{travelOrder}/edit', [UnitHeadOwnTravelOrderController::class, 'edit'])->name('unithead.travel-orders.edit');
    Route::put('/unithead/travel-orders/{travelOrder}', [UnitHeadOwnTravelOrderController::class, 'update'])->name('unithead.travel-orders.update');
    Route::delete('/unithead/travel-orders/{travelOrder}', [UnitHeadOwnTravelOrderController::class, 'destroy'])->name('unithead.travel-orders.destroy');
    
    // Travel Order Routes for Division Heads (own travel orders)
    Route::get('/divisionhead/travel-orders', [DivisionHeadOwnTravelOrderController::class, 'index'])->name('divisionhead.travel-orders.index');
    Route::get('/divisionhead/travel-orders/create', [DivisionHeadOwnTravelOrderController::class, 'create'])->name('divisionhead.travel-orders.create');
    Route::post('/divisionhead/travel-orders', [DivisionHeadOwnTravelOrderController::class, 'store'])->name('divisionhead.travel-orders.store');
    Route::get('/divisionhead/travel-orders/{travelOrder}', [DivisionHeadOwnTravelOrderController::class, 'show'])->name('divisionhead.travel-orders.show');
    Route::get('/divisionhead/travel-orders/{travelOrder}/edit', [DivisionHeadOwnTravelOrderController::class, 'edit'])->name('divisionhead.travel-orders.edit');
    Route::put('/divisionhead/travel-orders/{travelOrder}', [DivisionHeadOwnTravelOrderController::class, 'update'])->name('divisionhead.travel-orders.update');
    Route::delete('/divisionhead/travel-orders/{travelOrder}', [DivisionHeadOwnTravelOrderController::class, 'destroy'])->name('divisionhead.travel-orders.destroy');
    
    // Travel Order Approval Routes for Division Heads
    Route::get('/travel-orders/approvals/divisionhead', [DivisionHeadTravelOrderController::class, 'index'])->name('travel-orders.approvals.divisionhead');
    Route::get('/travel-orders/{travelOrder}/approval-show/divisionhead', [DivisionHeadTravelOrderController::class, 'show'])->name('travel-orders.approval-show.divisionhead');
    Route::put('/travel-orders/{travelOrder}/approve/divisionhead', [DivisionHeadTravelOrderController::class, 'approve'])->name('travel-orders.approve.divisionhead');
    Route::put('/travel-orders/{travelOrder}/reject/divisionhead', [DivisionHeadTravelOrderController::class, 'reject'])->name('travel-orders.reject.divisionhead');
    
    // Travel Order Routes for VPs (own travel orders)
    Route::get('/vp/travel-orders', [VpOwnTravelOrderController::class, 'index'])->name('vp.travel-orders.index');
    Route::get('/vp/travel-orders/create', [VpOwnTravelOrderController::class, 'create'])->name('vp.travel-orders.create');
    Route::post('/vp/travel-orders', [VpOwnTravelOrderController::class, 'store'])->name('vp.travel-orders.store');
    Route::get('/vp/travel-orders/{travelOrder}', [VpOwnTravelOrderController::class, 'show'])->name('vp.travel-orders.show');
    Route::get('/vp/travel-orders/{travelOrder}/edit', [VpOwnTravelOrderController::class, 'edit'])->name('vp.travel-orders.edit');
    Route::put('/vp/travel-orders/{travelOrder}', [VpOwnTravelOrderController::class, 'update'])->name('vp.travel-orders.update');
    Route::delete('/vp/travel-orders/{travelOrder}', [VpOwnTravelOrderController::class, 'destroy'])->name('vp.travel-orders.destroy');
    
    // Travel Order Approval Routes for VPs
    Route::get('/travel-orders/approvals/vp', [VpTravelOrderController::class, 'index'])->name('travel-orders.approvals.vp');
    Route::get('/travel-orders/{travelOrder}/approval-show/vp', [VpTravelOrderController::class, 'show'])->name('travel-orders.approval-show.vp');
    Route::put('/travel-orders/{travelOrder}/approve/vp', [VpTravelOrderController::class, 'approve'])->name('travel-orders.approve.vp');
    Route::put('/travel-orders/{travelOrder}/reject/vp', [VpTravelOrderController::class, 'reject'])->name('travel-orders.reject.vp');
    
    // Travel Order Routes for Presidents (own travel orders)
    Route::get('/president/travel-orders', [PresidentOwnTravelOrderController::class, 'index'])->name('president.travel-orders.index');
    Route::get('/president/travel-orders/create', [PresidentOwnTravelOrderController::class, 'create'])->name('president.travel-orders.create');
    Route::post('/president/travel-orders', [PresidentOwnTravelOrderController::class, 'store'])->name('president.travel-orders.store');
    Route::get('/president/travel-orders/{travelOrder}', [PresidentOwnTravelOrderController::class, 'show'])->name('president.travel-orders.show');
    Route::get('/president/travel-orders/{travelOrder}/edit', [PresidentOwnTravelOrderController::class, 'edit'])->name('president.travel-orders.edit');
    Route::put('/president/travel-orders/{travelOrder}', [PresidentOwnTravelOrderController::class, 'update'])->name('president.travel-orders.update');
    Route::delete('/president/travel-orders/{travelOrder}', [PresidentOwnTravelOrderController::class, 'destroy'])->name('president.travel-orders.destroy');
    
    // Travel Order Approval Routes for Presidents
    Route::get('/travel-orders/approvals/president', [PresidentTravelOrderController::class, 'index'])->name('travel-orders.approvals.president');
    Route::get('/travel-orders/{travelOrder}/approval-show/president', [PresidentTravelOrderController::class, 'show'])->name('travel-orders.approval-show.president');
    Route::put('/travel-orders/{travelOrder}/approve/president', [PresidentTravelOrderController::class, 'approve'])->name('travel-orders.approve.president');
    Route::put('/travel-orders/{travelOrder}/reject/president', [PresidentTravelOrderController::class, 'reject'])->name('travel-orders.reject.president');
    
    // Approved Travel Orders for Motorpool Admin
    Route::get('/approved-travel-orders', [MotorpoolAdminController::class, 'approvedTravelOrders'])->name('approved-travel-orders.index');
    
    // Vehicle Management Routes for Motorpool Admin
    Route::resource('vehicles', App\Http\Controllers\VehicleController::class);
    
    // Driver Management Routes for Motorpool Admin
    Route::resource('drivers', App\Http\Controllers\DriverController::class);
    
    // Trip Ticket Management Routes for Motorpool Admin
    Route::resource('trip-tickets', App\Http\Controllers\TripTicketController::class);
    
    // Test route for travel order creation
    Route::get('/test-create-travel-order', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return 'Not authenticated';
        }
        
        $employee = $user->employee;
        if (!$employee) {
            return 'User has no employee record';
        }
        
        return 'User: ' . $user->name . ', Employee: ' . $employee->first_name . ' ' . $employee->last_name;
    })->name('test-create-travel-order');
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