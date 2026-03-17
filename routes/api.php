<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

// Protected routes
Broadcast::routes(['middleware' => ['auth:api']]);

Route::middleware(['auth:api', 'scope:api', 'teams.permission'])->group(function () {

    Route::middleware(['company.active'])->group(function () {

        // Self management
        Route::prefix('manage')->group(function () {
            Route::get('/me', \App\Http\Controllers\Manage\GetMeController::class);
            Route::put('/me', \App\Http\Controllers\Manage\UpdateMeController::class);
        });

        // Administration
        Route::prefix('administration')->group(function () {
            // Users
            Route::middleware('permission:users.view')->group(function () {
                Route::get('/users', \App\Http\Controllers\Users\GetUsersController::class)->name('users.index');
                Route::get('/users/{user}', \App\Http\Controllers\Users\GetUserController::class)->name('users.show');
                Route::get('/users/{user}/auth-logs', \App\Http\Controllers\Users\GetUserAuthLogsController::class)->name('users.logs');
            });
            Route::post('/users', \App\Http\Controllers\Users\CreateUserController::class)->middleware('permission:users.create')->name('users.store');
            Route::put('/users/{user}', \App\Http\Controllers\Users\UpdateUserController::class)->middleware('permission:users.update')->name('users.update');
            Route::post('/users/{user}/avatar', \App\Http\Controllers\Users\UpdateUserAvatarController::class)->middleware('permission:users.update')->name('users.avatar');
            Route::delete('/users/{user}', \App\Http\Controllers\Users\DeleteUserController::class)->middleware('permission:users.delete')->name('users.destroy');

            // Permissions
            Route::get('/permissions', \App\Http\Controllers\Permissions\GetPermissionsController::class)
                ->middleware('permission:permissions.view')
                ->name('permissions.index');

            // Roles
            Route::get('/roles', \App\Http\Controllers\Roles\GetRolesController::class)->middleware('permission:permissions.view')->name('roles.index');
            Route::post('/roles', \App\Http\Controllers\Roles\CreateRoleController::class)->middleware('permission:permissions.manage')->name('roles.store');
            Route::put('/roles/{role}', \App\Http\Controllers\Roles\UpdateRoleController::class)->middleware('permission:permissions.manage')->name('roles.update');
            Route::delete('/roles/{role}', \App\Http\Controllers\Roles\DeleteRoleController::class)->middleware('permission:permissions.manage')->name('roles.destroy');
        });

        // File Manager
        Route::prefix('files')->group(function () {
            Route::middleware('permission:materials.view')->group(function () {
                Route::get('/', \App\Http\Controllers\FileManager\GetFileController::class)->name('files.index');
                Route::get('/{pathId}', \App\Http\Controllers\FileManager\GetFileController::class)->name('files.show');
                Route::get('/{pathId}/download', \App\Http\Controllers\FileManager\DownloadFileController::class)->name('files.download');
            });
            Route::post('/directory', \App\Http\Controllers\FileManager\CreateDirectoryController::class)->middleware('permission:materials.create')->name('files.directory.store');
            Route::post('/upload', \App\Http\Controllers\FileManager\UploadFileController::class)->middleware('permission:materials.create')->name('files.upload');
            Route::put('/{pathId}', \App\Http\Controllers\FileManager\UpdateItemController::class)->middleware('permission:materials.update')->name('files.update');
            Route::delete('/{pathId}', \App\Http\Controllers\FileManager\DeleteItemController::class)->middleware('permission:materials.delete')->name('files.destroy');
        });

        // Management
        Route::prefix('management')->group(function () {
            // Services
            Route::middleware('permission:services.view')->group(function () {
                Route::get('/services', \App\Http\Controllers\Services\GetServicesController::class)->name('services.index');
                Route::get('/services/{service}', \App\Http\Controllers\Services\GetServiceController::class)->name('services.show');
            });
            Route::post('/services', \App\Http\Controllers\Services\CreateServiceController::class)->middleware('permission:services.create')->name('services.store');
            Route::put('/services/{service}', \App\Http\Controllers\Services\UpdateServiceController::class)->middleware('permission:services.update')->name('services.update');
            Route::delete('/services/{service}', \App\Http\Controllers\Services\DeleteServiceController::class)->middleware('permission:services.delete')->name('services.destroy');

            // Proformas
            Route::middleware('permission:payments.view')->group(function () {
                Route::get('/proformas', \App\Http\Controllers\Proformas\GetProformasController::class)->name('proformas.index');
                Route::get('/proformas/{proforma}', \App\Http\Controllers\Proformas\GetProformaController::class)->name('proformas.show');
            });
            Route::post('/proformas', \App\Http\Controllers\Proformas\CreateProformaController::class)->middleware('permission:payments.create')->name('proformas.store');
            Route::put('/proformas/{proforma}', \App\Http\Controllers\Proformas\UpdateProformaController::class)->middleware('permission:payments.update')->name('proformas.update');
            Route::delete('/proformas/{proforma}', \App\Http\Controllers\Proformas\DeleteProformaController::class)->middleware('permission:payments.delete')->name('proformas.destroy');
            Route::post('/proformas/{proforma}/restore', \App\Http\Controllers\Proformas\RestoreProformaController::class)->middleware('permission:payments.update')->name('proformas.restore');
            Route::post('/proformas/{proforma}/confirm', \App\Http\Controllers\Proformas\ConfirmProformaController::class)->middleware('permission:payments.update')->name('proformas.confirm');

            // Invoices (confirmed — read-only + status update)
            Route::middleware('permission:payments.view')->group(function () {
                Route::get('/invoices', \App\Http\Controllers\Invoices\GetInvoicesController::class)->name('invoices.index');
                Route::get('/invoices/{invoice}', \App\Http\Controllers\Invoices\GetInvoiceController::class)->name('invoices.show');
                Route::get('/invoices/{invoice}/download', \App\Http\Controllers\Invoices\DownloadInvoiceController::class)->name('invoices.download');
            });
            Route::put('/invoices/{invoice}', \App\Http\Controllers\Invoices\UpdateInvoiceController::class)->middleware('permission:payments.update')->name('invoices.update');

            // Billing (one per company, auto-created with company)
            Route::get('/billing', \App\Http\Controllers\Billings\GetBillingController::class)->middleware('permission:settings.view')->name('billing.show');
            Route::put('/billing', \App\Http\Controllers\Billings\UpdateBillingController::class)->middleware('permission:settings.manage')->name('billing.update');
        });
    });
});
