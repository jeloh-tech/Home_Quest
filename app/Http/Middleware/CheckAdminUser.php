<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Please login to access admin panel.');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
