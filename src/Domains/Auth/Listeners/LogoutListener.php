<?php

namespace Auth\Listeners;

class LogoutListener
{
    /**
     * @param \Auth\Events\LogoutEvent $event
     * @return void
     */
    public function handle(\Auth\Events\LogoutEvent $event): void
    {
        \Auth\Models\AuthLog::create([
            'action' => 'logout',
            'model' => get_class($event->user),
            'user_id' => $event->user->id,
            'company_id' => $event->user->company_id ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
