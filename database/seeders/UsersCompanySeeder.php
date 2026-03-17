<?php

namespace Database\Seeders;

use Auth\Models\User;
use Administration\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;

class UsersCompanySeeder extends Seeder
{
    public function run(): void
    {
        // Reset Spatie permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Test company
        $company = Company::firstOrCreate(
            ['slug' => 'acme-corp'],
            [
                'name' => 'Acme Corporation',
                'email' => 'contact@acme.com',
                'phone' => '+48123456789',
                'city' => 'Warsaw',
                'country' => 'PL',
                'is_active' => true,
            ]
        );

        // Set active team (company) for Spatie
        setPermissionsTeamId($company->id);

        // Create default roles for the company
        $this->createDefaultRoles($company);

        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'Admin User',
                'password' => 'password',
                'role' => 'Administrator',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Administrator');

        // Teacher user
        $lector = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'Test Teacher',
                'password' => 'password',
                'role' => 'Teacher',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $lector->assignRole('Teacher');
    }

    private function createDefaultRoles(Company $company): void
    {
        $modules = Config::get('permission.modules', []);
        $allPermissionNames = collect($modules)->flatten()->toArray();

        // Role: Administrator (all permissions)
        $adminRole = Role::firstOrCreate([
            'name' => 'Administrator',
            'guard_name' => 'api',
            'company_id' => $company->id,
        ]);
        $adminRole->syncPermissions($allPermissionNames);

        // Role: Teacher
        $lektorRole = Role::firstOrCreate([
            'name' => 'Teacher',
            'guard_name' => 'api',
            'company_id' => $company->id,
        ]);
        $lektorRole->syncPermissions([
            'courses.view',
            'calendar.view', 'calendar.create', 'calendar.update',
            'materials.view',
        ]);

        // Role: Accountant
        $accountantRole = Role::firstOrCreate([
            'name' => 'Accountant',
            'guard_name' => 'api',
            'company_id' => $company->id,
        ]);
        $accountantRole->syncPermissions([
            'users.view',
            'payments.view', 'payments.create', 'payments.update',
        ]);
    }
}

