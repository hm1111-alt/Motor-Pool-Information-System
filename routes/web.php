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
use App\Http\Controllers\DriverController;
use App\Http\Controllers\DriverDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Auth;

// PUBLIC API ROUTES FOR VALIDATION (NO AUTHENTICATION REQUIRED)
Route::get('/api/check-contact-number/{contactNumber}', [DriverController::class, 'checkContactNumber'])->name('api.check-contact-number');
Route::get('/api/check-email/{email}', [DriverController::class, 'checkEmail'])->name('api.check-email');

Route::get('/api/offices', function() {
    try {
        $offices = \App\Models\Office::all();
        return response()->json($offices);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('api.offices');

Route::get('/', function () {
    return view('welcome');
});

// Debug route to check user-driver relationship
Route::get('/debug-driver-check', function() {
    if (!Auth::check()) {
        return 'Not logged in. Please log in first.';
    }
    
    $user = Auth::user();
    $driver = \App\Models\Driver::where('user_id', $user->id)->first();
    
    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'contact_num' => $user->contact_num,
            'role' => $user->role
        ],
        'driver' => $driver ? [
            'id' => $driver->id,
            'user_id' => $driver->user_id,
            'name' => $driver->first_name . ' ' . $driver->last_name,
            'driver_contact' => $driver->contact_num,
            'user_contact' => $driver->user->contact_num ?? 'N/A',
            'email' => $driver->user->email ?? 'N/A'
        ] : null,
        'message' => $driver ? 'Driver record found' : 'No driver record found'
    ]);
})->middleware('auth');

// Test route to check all drivers data
Route::get('/debug-all-drivers', function() {
    $drivers = \App\Models\Driver::with('user')->get();
    
    $result = [];
    foreach($drivers as $driver) {
        $result[] = [
            'driver_id' => $driver->id,
            'driver_name' => $driver->full_name,
            'user_id' => $driver->user_id,
            'user_name' => $driver->user ? $driver->user->name : 'No user',
            'driver_contact' => $driver->contact_num,
            'user_contact' => $driver->user ? $driver->user->contact_num : 'No contact',
            'user_email' => $driver->user ? $driver->user->email : 'No email'
        ];
    }
    
    return response()->json($result);
});

// Test route for vehicle add button
Route::get('/vehicles/test-add-button', function () {
    return view('vehicles.test-add-button');
})->name('vehicles.test-add-button');

// Driver Dashboard Routes (Accessible only to drivers)
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/itineraries', [DriverDashboardController::class, 'itineraries'])->name('itineraries');
    Route::get('/trip-tickets', [DriverDashboardController::class, 'tripTickets'])->name('trip-tickets');
    Route::get('/calendar/events', [DriverDashboardController::class, 'getCalendarEvents'])->name('calendar.events');
});

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
    Route::get('/admin/employees/{employee}', [EmployeeController::class, 'show'])->name('admin.employees.show');
    Route::post('/admin/employees', [EmployeeController::class, 'store'])->name('admin.employees.store');
    Route::get('/admin/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('admin.employees.edit');
    Route::put('/admin/employees/{employee}', [EmployeeController::class, 'update'])->name('admin.employees.update');
    Route::delete('/admin/employees/{employee}', [EmployeeController::class, 'destroy'])->name('admin.employees.destroy');
    
    // AJAX routes for employee form dependencies - moved inside auth middleware
    Route::get('/admin/employees/get-divisions-by-office', [EmployeeController::class, 'getDivisionsByOffice'])->name('admin.employees.get-divisions-by-office');
    Route::get('/admin/employees/get-units-by-division', [EmployeeController::class, 'getUnitsByDivision'])->name('admin.employees.get-units-by-division');
    Route::get('/admin/employees/get-subunits-by-unit', [EmployeeController::class, 'getSubunitsByUnit'])->name('admin.employees.get-subunits-by-unit');
    

    
    // Leadership Routes
    Route::get('/admin/leaders', [LeaderController::class, 'index'])->name('admin.leaders.index');
    Route::get('/admin/leaders/offices', [LeaderController::class, 'offices'])->name('admin.leaders.offices');
    Route::get('/admin/leaders/offices/{office}', [LeaderController::class, 'showOffice'])->name('admin.leaders.office.show');
    Route::get('/admin/leaders/divisions/{division}', [LeaderController::class, 'showDivision'])->name('admin.leaders.division.show');
    Route::get('/admin/leaders/units/{unit}', [LeaderController::class, 'showUnit'])->name('admin.leaders.unit.show');
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
    Route::get('/api/travel-orders/search', [MotorpoolAdminController::class, 'searchTravelOrders'])->name('api.travel-orders.search');
    
    // API Routes for Calendar and Status Counts
    Route::get('/api/trip-tickets/calendar-events', [MotorpoolAdminController::class, 'calendarEvents'])->name('api.trip-tickets.calendar-events');
    Route::get('/api/trip-tickets/status-counts', [MotorpoolAdminController::class, 'statusCounts'])->name('api.trip-tickets.status-counts');
    
    // Vehicle Management Routes for Motorpool Admin
    Route::resource('vehicles', App\Http\Controllers\VehicleController::class);
    
    // Driver Management Routes for Motorpool Admin
    Route::resource('drivers', DriverController::class);
    Route::get('/drivers/generate-pdf', [DriverController::class, 'generatePDF'])->name('drivers.generate-pdf');
    
    // Trip Ticket Management Routes for Motorpool Admin
    Route::resource('trip-tickets', App\Http\Controllers\TripTicketController::class);
    Route::patch('/trip-tickets/{id}/status', [App\Http\Controllers\TripTicketController::class, 'updateStatus'])->name('trip-tickets.status.update');
    Route::get('/api/travel-orders/{id}/passengers', [App\Http\Controllers\TripTicketController::class, 'getPassengersForTravelOrder']);
    Route::get('/api/travel-orders/{id}/creator', [App\Http\Controllers\RegularEmployeeTravelOrderController::class, 'getCreator']);
    Route::get('/api/travel-orders/{id}', function($id) {
        $travelOrder = \App\Models\TravelOrder::with('employee')->find($id);
        
        if (!$travelOrder) {
            return response()->json(['error' => 'Travel order not found'], 404);
        }
        
        return response()->json([
            'id' => $travelOrder->id,
            'date_from' => $travelOrder->date_from,
            'date_to' => $travelOrder->date_to,
            'departure_time' => $travelOrder->departure_time,
            'destination' => $travelOrder->destination,
            'purpose' => $travelOrder->purpose,
            'employee' => $travelOrder->employee ? [
                'first_name' => $travelOrder->employee->first_name,
                'last_name' => $travelOrder->employee->last_name
            ] : null
        ]);
    });
    
    Route::get('/api/available-drivers/{date}', function($date) {
        $tripTickets = \App\Models\TripTicket::whereHas('itinerary', function($query) use ($date) {
            $query->whereRaw("DATE(CONCAT(itineraries.date_from, ' ', itineraries.departure_time)) <= ?", [$date])
                  ->whereRaw("DATE(CONCAT(itineraries.date_to, ' ', '23:59:59')) >= ?", [$date]);
        })->pluck('itinerary.driver_id');
        
        $availableDrivers = \App\Models\Driver::where('availability_status', 'Available')
            ->whereNotIn('id', $tripTickets)
            ->get();
        
        return response()->json($availableDrivers);
    });
    
    Route::get('/api/available-vehicles/{date}', function($date) {
        $tripTickets = \App\Models\TripTicket::whereHas('itinerary', function($query) use ($date) {
            $query->whereRaw("DATE(CONCAT(itineraries.date_from, ' ', itineraries.departure_time)) <= ?", [$date])
                  ->whereRaw("DATE(CONCAT(itineraries.date_to, ' ', '23:59:59')) >= ?", [$date]);
        })->pluck('itinerary.vehicle_id');
        
        $availableVehicles = \App\Models\Vehicle::whereIn('status', ['Available', 'Active'])
            ->whereNotIn('id', $tripTickets)
            ->get();
        
        return response()->json($availableVehicles);
    });
    
    Route::get('/api/itineraries/{id}', function($id) {
        $itinerary = \App\Models\Itinerary::with(['driver', 'vehicle', 'travelOrder'])->find($id);
        
        if (!$itinerary) {
            return response()->json([
                'success' => false,
                'message' => 'Itinerary not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'itinerary' => $itinerary
        ]);
    });
    Route::get('/api/itineraries/{id}/creator', [App\Http\Controllers\ItineraryController::class, 'getCreator']);

    // Itinerary Management Routes for Motorpool Admin
    Route::resource('itinerary', App\Http\Controllers\ItineraryController::class);
    Route::get('/itinerary/create/modal', [App\Http\Controllers\ItineraryController::class, 'createModal'])->name('itinerary.create.modal');
    
    // Itinerary Approval Routes
    Route::prefix('itinerary/approvals')->name('itinerary.approvals.')->group(function () {
        Route::get('/unit-head-pending', [App\Http\Controllers\ItineraryApprovalController::class, 'unitHeadPending'])->name('unit-head.pending');
        Route::put('/{id}/unit-head-approve', [App\Http\Controllers\ItineraryApprovalController::class, 'approveByUnitHead'])->name('unit-head.approve');
        Route::put('/{id}/unit-head-reject', [App\Http\Controllers\ItineraryApprovalController::class, 'rejectByUnitHead'])->name('unit-head.reject');
        Route::get('/vp-pending', [App\Http\Controllers\ItineraryApprovalController::class, 'vpPending'])->name('vp.pending');
        Route::put('/{id}/vp-approve', [App\Http\Controllers\ItineraryApprovalController::class, 'approveByVp'])->name('vp.approve');
        Route::put('/{id}/vp-reject', [App\Http\Controllers\ItineraryApprovalController::class, 'rejectByVp'])->name('vp.reject');
        Route::get('/approved', [App\Http\Controllers\ItineraryApprovalController::class, 'approved'])->name('approved');
    });
    
    // Motorpool Admin Dashboard Approval Routes
    Route::get('/motorpool/approvals/itineraries/unit-head', [App\Http\Controllers\ItineraryApprovalController::class, 'unitHeadPending'])->name('motorpool.approvals.itineraries.unit-head');
    Route::get('/motorpool/approvals/itineraries/vp', [App\Http\Controllers\ItineraryApprovalController::class, 'vpPending'])->name('motorpool.approvals.itineraries.vp');
    Route::get('/motorpool/approvals/itineraries/approved', [App\Http\Controllers\ItineraryApprovalController::class, 'approved'])->name('motorpool.approvals.itineraries.approved');
    
    // VP Trip Ticket Approval Routes
    Route::prefix('vp/approvals/trip-tickets')->name('vp.trip-tickets.approvals.')->group(function () {
        Route::get('/', [App\Http\Controllers\VpTripTicketApprovalController::class, 'index'])->name('index');
        Route::get('/{tripTicket}', [App\Http\Controllers\VpTripTicketApprovalController::class, 'show'])->name('show');
        Route::put('/{tripTicket}/approve', [App\Http\Controllers\VpTripTicketApprovalController::class, 'approve'])->name('approve');
        Route::put('/{tripTicket}/reject', [App\Http\Controllers\VpTripTicketApprovalController::class, 'reject'])->name('reject');
    });
    
    // Employee Trip Ticket Routes
    Route::prefix('employee/trip-tickets')->name('employee.trip-tickets.')->group(function () {
        Route::get('/', [App\Http\Controllers\EmployeeTripTicketController::class, 'index'])->name('index');
        Route::get('/{tripTicket}', [App\Http\Controllers\EmployeeTripTicketController::class, 'show'])->name('show');
    });
    
    // Vehicle Travel History Routes
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/travel-history', [App\Http\Controllers\VehicleTravelHistoryController::class, 'index'])->name('travel-history');
    });
    
    // Vehicle Maintenance Routes
    Route::resource('vehicle-maintenance', App\Http\Controllers\VehicleMaintenanceController::class);
    Route::get('/vehicles/{vehicle}/maintenance', [App\Http\Controllers\VehicleMaintenanceController::class, 'maintenanceByVehicle'])->name('vehicles.maintenance');
    
    // Travel Order Management Routes for Motorpool Admin
    Route::prefix('motorpool')->name('motorpool.')->group(function () {
        Route::get('/dashboard', [MotorpoolAdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('travel-orders', App\Http\Controllers\MotorpoolAdminTravelOrderController::class)->only(['index', 'show']);
    });

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


Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

require __DIR__.'/auth.php';