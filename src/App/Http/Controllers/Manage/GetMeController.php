<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class GetMeController
{
    /**
     * Return the authenticated user's profile with roles and permissions.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'company_id' => $user->company_id,
            'is_active' => $user->is_active,
            'avatar_url' => $user->avatar_url,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getAllPermissions()->pluck('name'),
        ]);
    }
}
