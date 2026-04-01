<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PanitiaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::get('panitia_login')) {
            return redirect()->route('panitia.login');
        }

        return $next($request);
    }
}