<?php

namespace Administration\Services;

use Auth\Repositories\AuthLogRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Administration\Services\Interfaces\UserActivityServiceInterface;

readonly class UserActivityService implements UserActivityServiceInterface
{
    private const ONLINE_THRESHOLD_MINUTES = 15;

    public function __construct(
        private AuthLogRepositoryInterface $authLogRepository,
    ) {}

    /**
     * Get paginated authorization logs for a user.
     *
     * @param int $userId
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getUserAuthLogs(int $userId, array $params = []): LengthAwarePaginator
    {
        $activities = $this->authLogRepository->byUser($userId);

        return $activities->paginate(self::ONLINE_THRESHOLD_MINUTES);
    }
}
