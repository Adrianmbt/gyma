<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('usuario_id')) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
