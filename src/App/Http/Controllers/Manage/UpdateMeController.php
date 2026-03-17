<?php

namespace App\Http\Controllers\Manage;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

readonly class UpdateMeController
{
    /**
     * Update the authenticated user's profile.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        // TODO: implement profile update
        return response()->json('ok');
    }
}
