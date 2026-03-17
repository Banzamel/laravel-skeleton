<?php

namespace Auth\Observers;

use Auth\Models\AuthLog;

class AuthLogObserver
{
    /**
     * Handle the AuthLog "creating" event to set default user and company.
     *
     * @param AuthLog $authLog
     * @return void
     */
    public function creating(AuthLog $authLog): void
    {
        $authLog->user_id = $authLog->user_id ?? request()->user()?->id;
        $authLog->company_id = $authLog->company_id ?? request()->user()?->company_id;
    }
}
