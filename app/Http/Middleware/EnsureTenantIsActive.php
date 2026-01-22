<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next)
    {
        $tenant = tenant(); // من stancl

        if (!$tenant || !$tenant->is_active) {
            abort(403, 'Tenant inactive');
        }

        if ($tenant->subscription_ends_at &&
            now()->gt($tenant->subscription_ends_at)) {
            abort(402, 'Subscription expired');
        }

        return $next($request);
    }
}
