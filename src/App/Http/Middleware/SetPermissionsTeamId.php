<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetPermissionsTeamId
{
    /**
     * Set the team ID (company_id) for the Spatie permissions system.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()) {
            setPermissionsTeamId($request->user()->company_id);
        }

        return $next($request);
    }
}
