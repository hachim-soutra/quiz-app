<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->userable_type == User::CLIENT_TYPE)
        {
            return $next($request);
        }
        if(Auth::user()->userable_type == User::ADMIN_TYPE)
        {
            return redirect()->route('home');
        }
        abort(401);
    }
}
