<?php

namespace Auth\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface AuthLogRepositoryInterface
{
    public function byUser(int $userId): Builder;
    public function latestActivity(): ?string;
}
