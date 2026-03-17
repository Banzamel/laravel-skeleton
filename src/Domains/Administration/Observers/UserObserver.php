<?php

namespace Administration\Observers;

use Administration\Models\User;
use Shared\Exceptions\ApiJsonException;

class UserObserver
{
    /**
     * Handle the User "deleting" event.
     *
     * @param User $user
     * @return void
     * @throws ApiJsonException
     */
    public function deleting(User $user): void
    {
        if (auth()->check() && $user->id === auth()->id()) {
            throw new ApiJsonException('Cannot delete your own account', 403);
        }
    }
}
