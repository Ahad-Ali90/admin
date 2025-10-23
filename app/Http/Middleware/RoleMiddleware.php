<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Check if user has the required role
        if (!$this->hasRole($user, $role)) {
            abort(403, 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }

    /**
     * Check if user has the required role
     */
    private function hasRole($user, string $role): bool
    {
        // Admin has access to everything
        if ($user->isAdmin()) {
            return true;
        }

        // Check specific role
        switch ($role) {
            case 'admin':
                return $user->isAdmin();
            case 'booking_grabber':
                return $user->isBookingGrabber() || $user->isAdmin();
            case 'driver':
                return $user->isDriver() || $user->isAdmin();
            case 'porter':
                return $user->isPorter() || $user->isAdmin();
            case 'staff':
                return $user->isStaff() || $user->isAdmin();
            default:
                return false;
        }
    }
}
