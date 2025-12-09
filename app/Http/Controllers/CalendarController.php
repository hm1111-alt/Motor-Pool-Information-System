<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display the calendar.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        
        // Get current month and year
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        return view('calendar.index', compact('currentMonth', 'currentYear'));
    }
    
    /**
     * API endpoint to get events for calendar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        // Get date range from request or use current month
        $start = $request->get('start', now()->startOfMonth()->toDateString());
        $end = $request->get('end', now()->endOfMonth()->toDateString());
        
        // Return empty events array since we're not using travel orders
        $events = [];
        
        return response()->json($events);
    }
}