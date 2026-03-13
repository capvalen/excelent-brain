<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Allows PDF routes (opened via window.open / <a href>) to authenticate
 * by accepting the Sanctum token as a ?token= query parameter.
 * The token is injected as an Authorization: Bearer header so the
 * standard auth:sanctum guard works without any other changes.
 */
class InjectTokenFromQuery
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');

        if ($token && !$request->headers->has('Authorization')) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
            Log::info('[InjectTokenFromQuery] Token injected for ' . $request->path());
        }

        return $next($request);
    }
}
