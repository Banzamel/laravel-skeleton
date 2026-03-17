<?php

namespace Shared\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckCompanyNotExpired
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle($request, Closure $next): mixed
    {
        $user = Auth::user();

        if ($user && $user->company && $user->company->isExpired()) {
            return response()->json(['message' => 'Your company subscription has expired.'], 403);
        }

        return $next($request);
    }
}
