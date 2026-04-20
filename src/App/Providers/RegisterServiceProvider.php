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
        \Auth\Repositories\Interfaces\AuthLogRepositoryInterface::class => \Auth\Repositories\AuthLogRepository::class,
        \Administration\Repositories\Interfaces\UserRepositoryInterface::class => \Administration\Repositories\UserRepository::class,
        \Administration\Repositories\Interfaces\RoleRepositoryInterface::class => \Administration\Repositories\RoleRepository::class,
        \Payment\Repositories\Interfaces\ServiceRepositoryInterface::class => \Payment\Repositories\ServiceRepository::class,
        \Payment\Repositories\Interfaces\InvoiceRepositoryInterface::class => \Payment\Repositories\InvoiceRepository::class,
        \Payment\Repositories\Interfaces\ProformaRepositoryInterface::class => \Payment\Repositories\ProformaRepository::class,
        \Payment\Repositories\Interfaces\BillingRepositoryInterface::class => \Payment\Repositories\BillingRepository::class,
        \FileManager\Repositories\Interfaces\FileManagerRepositoryInterface::class => \FileManager\Repositories\FileManagerRepository::class,
    ];
}
