<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Administration\Services\Interfaces\UserActivityServiceInterface;

readonly class GetUserAuthLogsController
{
    /**
     * @param UserActivityServiceInterface $activityService
     */
    public function __construct(private UserActivityServiceInterface $activityService)
    {
    }

    /**
     * Get paginated authorization logs for a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, int $user): JsonResponse
    {
        $params = $request->only(['page', 'limit']);
        $logs = $this->activityService->getUserAuthLogs($user, $params);

        return response()->json($logs);
    }
}
