<?php

namespace App\Http\Middleware;

use App\Models\Property;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPropertyView
{
    /**
     * If a property show page is reached, the controller already increments
     * the view counter. This middleware exists so we can attach extra
     * analytics (e.g. unique-visitor tracking) without touching controllers.
     * For now it just passes through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
