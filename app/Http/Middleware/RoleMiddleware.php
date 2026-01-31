<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Driver;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Handle driver role
        if ($role === 'driver') {
            $driver = Driver::where('user_id', $user->id)->first();
            if (!$driver) {
                return redirect()->route('dashboard')
                    ->with('error', 'Access denied. You are not registered as a driver.');
            }
            return $next($request);
        }
        
        // Handle other roles (existing logic)
        if ($user->role !== $role) {
            return redirect()->route('dashboard')
                ->with('error', 'Access denied. Insufficient permissions.');
        }
        
        return $next($request);
    }
}