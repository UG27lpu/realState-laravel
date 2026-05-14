<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPropertyView
{
    /**
     * Placeholder hook: real property-view tracking is wired up once the
     * Property model exists. Kept here so the middleware stack is final from
     * the bootstrap commit.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
