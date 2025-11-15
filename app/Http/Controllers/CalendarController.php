<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\TravelOrder;

class CalendarController extends Controller
{
    /**
     * Display the calendar with approved travel orders.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $employeeId = $user->employee->id ?? null;
        
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        return view('calendar.index', compact('currentMonth', 'currentYear'));
    }
    
    /**
     * API endpoint to get travel orders for calendar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(Request $request): JsonResponse
    {
        $user = Auth::user();
        $employeeId = $user->employee->id ?? null;
        
        // Get date range from request or use current month
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());
        
        // Get approved travel orders for the current employee within date range
        $approvedTravelOrders = TravelOrder::where('employee_id', $employeeId)
            ->where('divisionhead_approved', 1)
            ->where('vp_approved', 1)
            ->whereBetween('date_from', [$start, $end])
            ->orderBy('date_from', 'asc')
            ->get();
        
        // Format events for FullCalendar
        $events = [];
        foreach ($approvedTravelOrders as $order) {
            $events[] = [
                'id' => $order->id,
                'title' => $order->purpose,
                'start' => $order->date_from->toDateTimeString(),
                'end' => $order->date_to->toDateTimeString(),
                'destination' => $order->destination,
                'allDay' => true,
                'extendedProps' => [
                    'destination' => $order->destination,
                ]
            ];
        }
        
        return response()->json($events);
    }
}