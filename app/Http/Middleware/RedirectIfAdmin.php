<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    /**
     * Keep the admin section behind an explicit admin check. Anyone reaching
     * an admin route without the admin role is bounced to the user dashboard.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        if (! method_exists($user, 'hasRole') || ! $user->hasRole('admin')) {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
