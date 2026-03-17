<?php

namespace Auth\Listeners;

class LoginListener
{
    /**
     * Handle the login event by creating an auth log entry.
     *
     * @param \Auth\Events\LoginEvent $event
     * @return void
     */
    public function handle(\Auth\Events\LoginEvent $event): void
    {
        \Auth\Models\AuthLog::create([
            'action' => 'login',
            'model' => get_class($event->user),
            'user_id' => $event->user->id,
            'company_id' => $event->user->company_id ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
