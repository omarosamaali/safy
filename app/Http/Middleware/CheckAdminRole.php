<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) 
    { 
      if (Auth::check()) { 
        view()->share('loggedInUser', Auth::user()); 
        view()->share('adminRole', Auth::user()->role_for_messages === 'admin'); 
      } 
    
      return $next($request);
    }
}
