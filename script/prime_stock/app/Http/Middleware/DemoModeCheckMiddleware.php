<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoModeCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(appMode() == 'demo'){
            if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
                session()->flash('error', 'Demo mode is enabled. You cannot perform this action.');
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
