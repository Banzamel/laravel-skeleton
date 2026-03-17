<?php

namespace Auth\Repositories;

use Auth\Models\AuthLog;
use Illuminate\Database\Eloquent\Builder;

class AuthLogRepository implements AuthLogRepositoryInterface
{
    public function byUser(int $userId): Builder
    {
        return AuthLog::query()
            ->where('user_id', $userId);
    }

    public function latestActivity(): ?string
    {
        return AuthLog::query()
            ->latest('created_at')
            ->value('created_at');
    }
}
