<?php

namespace Administration\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Administration\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Administration\Dtos\UserCreateDto;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param int $userId
     * @param array $with
     * @return User
     */
    public function findOrFail(int $userId, array $with = []): User
    {
        return User::query()
            ->when(!empty($with), fn($q) => $q->with($with))
            ->findOrFail($userId);
    }

    /**
     * @param UserCreateDto $dto
     * @return User
     */
    public function create(UserCreateDto $dto): User
    {
        return User::create([
            'email_verified_at' => now(),
            ...$dto->toArray(),
        ]);
    }

    /**
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }

    /**
     * @param User $user
     * @param UploadedFile $avatar
     * @return User
     */
    public function updateAvatar(User $user, UploadedFile $avatar): User
    {
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $avatar->storePublicly("avatars/{$user->company_id}", 'public');
        $user->update(['avatar_path' => $path]);

        return $user->fresh();
    }
}
