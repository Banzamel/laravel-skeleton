<?php

namespace Auth\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface AuthLogRepositoryInterface
{
    public function byUser(int $userId): Builder;
    public function latestActivity(): ?string;
}
