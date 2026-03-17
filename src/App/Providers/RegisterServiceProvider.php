<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RegisterServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Services
        \Auth\Services\Interfaces\AuthorizationServiceInterface::class => \Auth\Services\AuthorizationService::class,
        \Auth\Services\Interfaces\SocialAuthServiceInterface::class => \Auth\Services\SocialAuthService::class,
        \Administration\Services\Interfaces\RoleServiceInterface::class => \Administration\Services\RoleService::class,
        \Administration\Services\Interfaces\PermissionServiceInterface::class => \Administration\Services\PermissionService::class,
        \Administration\Services\Interfaces\UserManagementServiceInterface::class => \Administration\Services\UserManagementService::class,
        \Administration\Services\Interfaces\UserActivityServiceInterface::class => \Administration\Services\UserActivityService::class,
        \Payment\Services\Interfaces\ServiceManagementServiceInterface::class => \Payment\Services\ServiceManagementService::class,
        \Payment\Services\Interfaces\InvoiceServiceInterface::class => \Payment\Services\InvoiceService::class,
        \Payment\Services\Interfaces\ProformaServiceInterface::class => \Payment\Services\ProformaService::class,
        \Payment\Services\Interfaces\BillingServiceInterface::class => \Payment\Services\BillingService::class,
        \FileManager\Services\Interfaces\FileManagerServiceInterface::class => \FileManager\Services\FileManagerService::class,

        // Repositories
        \Auth\Repositories\AuthLogRepositoryInterface::class => \Auth\Repositories\AuthLogRepository::class,
        \Administration\Repositories\UserRepositoryInterface::class => \Administration\Repositories\UserRepository::class,
        \Administration\Repositories\RoleRepositoryInterface::class => \Administration\Repositories\RoleRepository::class,
        \Payment\Repositories\ServiceRepositoryInterface::class => \Payment\Repositories\ServiceRepository::class,
        \Payment\Repositories\InvoiceRepositoryInterface::class => \Payment\Repositories\InvoiceRepository::class,
        \Payment\Repositories\ProformaRepositoryInterface::class => \Payment\Repositories\ProformaRepository::class,
        \Payment\Repositories\BillingRepositoryInterface::class => \Payment\Repositories\BillingRepository::class,
        \FileManager\Repositories\FileManagerRepositoryInterface::class => \FileManager\Repositories\FileManagerRepository::class,
    ];
}
