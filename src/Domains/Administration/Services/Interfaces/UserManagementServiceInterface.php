<?php

namespace Administration\Services\Interfaces;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Administration\Dtos\UserCreateDto;
use Administration\Dtos\UserUpdateDto;
use Administration\Models\User;

interface UserManagementServiceInterface
{
    /**
     * Get all users for the company.
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getAllUsers(array $params = []): LengthAwarePaginator;

    /**
     * Get a user by ID.
     *
     * @param int $userId
     * @return User
     */
    public function getUserById(int $userId): User;

    /**
     * Create a new user in the company.
     *
     * @param UserCreateDto $dto
     * @return User
     */
    public function createUser(UserCreateDto $dto): User;

    /**
     * Update a user in the company.
     *
     * @param int $userId
     * @param UserUpdateDto $dto
     * @return User
     */
    public function updateUser(int $userId, UserUpdateDto $dto): User;

    /**
     * Delete a user from the company.
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool;

    /**
     * Update a user's avatar.
     *
     * @param int $userId
     * @param UploadedFile $avatar
     * @return User
     */
    public function updateUserAvatar(int $userId, UploadedFile $avatar): User;
}
