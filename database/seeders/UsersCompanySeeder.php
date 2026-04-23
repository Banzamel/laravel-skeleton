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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $company = Company::firstOrCreate(
            ['slug' => 'default-company'],
            [
                'name' => 'Default Company',
                'email' => 'contact@example.com',
                'phone' => '+48000000000',
                'city' => 'Warsaw',
                'country' => 'PL',
                'is_active' => true,
            ]
        );

        setPermissionsTeamId($company->id);

        $this->createDefaultRoles($company);

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'Manager',
                'password' => 'password',
                'role' => 'manager',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $manager->assignRole('manager');

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'company_id' => $company->id,
                'name' => 'User',
                'password' => 'password',
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('user');
    }

    private function createDefaultRoles(Company $company): void
    {
        $modules = Config::get('permission.modules', []);
        $allPermissionNames = collect($modules)->flatten()->toArray();

        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'api',
            'company_id' => $company->id,
        ]);
        $adminRole->syncPermissions($allPermissionNames);

        $managerRole = Role::firstOrCreate([
            'name' => 'manager',
            'guard_name' => 'api',
            'company_id' => $company->id,
        ]);
        $managerRole->syncPermissions([
            'users.view',
            'services.view', 'services.create', 'services.update',
            'payments.view', 'payments.create', 'payments.update',
            'files.view', 'files.create', 'files.update',
            'settings.view',
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'api',
            'company_id' => $company->id,
        ]);
        $userRole->syncPermissions([
            'services.view',
            'payments.view',
            'files.view',
        ]);
    }
}
