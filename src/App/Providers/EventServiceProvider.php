<?php

namespace App\Providers;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        // Auth
        \Auth\Events\LoginEvent::class => [
            \Auth\Listeners\LoginListener::class
        ],
        \Auth\Events\LogoutEvent::class => [
            \Auth\Listeners\LogoutListener::class
        ],

        // Administration
        \Administration\Events\UserCreatedEvent::class => [],
        \Administration\Events\UserUpdatedEvent::class => [],
        \Administration\Events\UserDeletedEvent::class => [],
        \Administration\Events\UserAvatarUpdatedEvent::class => [],
        \Administration\Events\RoleCreatedEvent::class => [],
        \Administration\Events\RoleUpdatedEvent::class => [],
        \Administration\Events\RoleDeletedEvent::class => [],

        // Payment
        \Payment\Events\ServiceCreatedEvent::class => [],
        \Payment\Events\ServiceUpdatedEvent::class => [],
        \Payment\Events\ServiceDeletedEvent::class => [],
        \Payment\Events\InvoiceUpdatedEvent::class => [],
        \Payment\Events\ProformaCreatedEvent::class => [],
        \Payment\Events\ProformaUpdatedEvent::class => [],
        \Payment\Events\ProformaDeletedEvent::class => [],
        \Payment\Events\ProformaRestoredEvent::class => [],
        \Payment\Events\ProformaConfirmedEvent::class => [],
        \Payment\Events\BillingUpdatedEvent::class => [],

        // FileManager
        \FileManager\Events\FileUploadEvent::class => [],
    ];
}
