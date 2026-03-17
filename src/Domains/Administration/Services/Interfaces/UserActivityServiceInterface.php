<?php

namespace Administration\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;

interface UserActivityServiceInterface
{
    /**
     * Get paginated authorization logs for a user.
     *
     * @param int $userId
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getUserAuthLogs(int $userId, array $params = []): LengthAwarePaginator;
}
