<?php

namespace Administration\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Administration\Dtos\UserCreateDto;
use Administration\Dtos\UserUpdateDto;
use Administration\Events\UserAvatarUpdatedEvent;
use Administration\Events\UserCreatedEvent;
use Administration\Events\UserDeletedEvent;
use Administration\Events\UserUpdatedEvent;
use Administration\Models\User;
use Administration\Repositories\UserRepositoryInterface;
use Administration\Services\Interfaces\UserActivityServiceInterface;
use Administration\Services\Interfaces\UserManagementServiceInterface;
use Shared\Exceptions\ApiJsonException;

readonly class UserManagementService implements UserManagementServiceInterface
{
    /**
     * @param UserRepositoryInterface $userRepository
     * @param UserActivityServiceInterface $activityService
     */
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly UserActivityServiceInterface $activityService,
    ) {}

    /**
     * Get all company users.
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getAllUsers(array $params = []): LengthAwarePaginator
    {
        //get all users from user company
        $users = User::query()->with([]);

        return $users->paginate();
    }

    /**
     * Get a user by id with permissions.
     *
     * @param int $userId
     * @return User
     */
    public function getUserById(int $userId): User
    {
        return $this->userRepository->findOrFail($userId, ['roles:id,name', 'permissions:id,name']);
    }

    /**
     * Create a new user in the company with a role assignment.
     *
     * @param UserCreateDto $dto
     * @return User
     */
    public function createUser(UserCreateDto $dto): User
    {
        $user = $this->userRepository->create($dto);

        setPermissionsTeamId(auth()->user()->company_id);

        $user->assignRole($dto->getRoleName());
        $user->load('roles:id,name');

        event(new UserCreatedEvent($user, auth()->user()));

        return $user;
    }

    /**
     * Update a company user with optional role change.
     *
     * @param int $userId
     * @param UserUpdateDto $dto
     * @return User
     * @throws ApiJsonException
     */
    public function updateUser(int $userId, UserUpdateDto $dto): User
    {
        $user = $this->userRepository->findOrFail($userId);
        $user = $this->userRepository->update($user, $dto->toArray());

        $user->load('roles:id,name');

        event(new UserUpdatedEvent($user, auth()->user()));

        return $user;
    }

    /**
     * Delete a company user (cannot delete yourself).
     *
     * @param int $userId
     * @return bool
     * @throws ApiJsonException
     */
    public function deleteUser(int $userId): bool
    {
        $user = $this->userRepository->findOrFail($userId);

        event(new UserDeletedEvent($user, auth()->user()));

        return $this->userRepository->delete($user);
    }

    /**
     * Update a user avatar.
     *
     * @param int $userId
     * @param UploadedFile $avatar
     * @return User
     */
    public function updateUserAvatar(int $userId, UploadedFile $avatar): User
    {
        $user = $this->userRepository->findOrFail($userId);
        $user = $this->userRepository->updateAvatar($user, $avatar);

        $user->load('roles:id,name');

        event(new UserAvatarUpdatedEvent($user, auth()->user()));

        return $user;
    }
}
