<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WarehouseAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!checkWarehouseAdmin()){
            return redirect()->route('dashboard')->with('success', 'You are a Warehouse Admin. You cannot access it.');
        }
        return $next($request);
    }
}
