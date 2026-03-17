<?php

namespace Administration\Repositories;

use Administration\Models\User;
use Illuminate\Http\UploadedFile;
use Administration\Dtos\UserCreateDto;

interface UserRepositoryInterface
{
    public function findOrFail(int $userId, array $with = []): User;
    public function create(UserCreateDto $dto): User;
    public function update(User $user, array $data): User;
    public function delete(User $user): bool;
    public function updateAvatar(User $user, UploadedFile $avatar): User;
}
