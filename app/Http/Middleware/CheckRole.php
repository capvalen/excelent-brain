<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:administrador')
     *        ->middleware('role:administrador,recepcionista')
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        // Laravel passes comma-separated middleware params as individual arguments,
        // so ->middleware('role:administrador,recepcionista') calls handle(..., 'administrador', 'recepcionista')
        if (! $request->user() || ! in_array($request->user()->rol, $roles)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
