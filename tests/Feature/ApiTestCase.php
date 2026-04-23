<?php

namespace Tests\Feature;

use Auth\Models\User;
use Database\Seeders\RoleAndPermissionsSeeder;
use Database\Seeders\UsersCompanySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Administration\Models\Company;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $manager;
    protected User $user;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionsSeeder::class);
        $this->seed(UsersCompanySeeder::class);

        $this->company = Company::where('slug', 'default-company')->first();
        DB::table('sec_companies')
            ->where('id', $this->company->id)
            ->update(['expired_at' => now()->addYear()]);
        $this->company->refresh();

        $this->admin = User::where('email', 'admin@example.com')->first();
        $this->manager = User::where('email', 'manager@example.com')->first();
        $this->user = User::where('email', 'user@example.com')->first();
    }

    protected function actingAsAdmin(): static
    {
        Passport::actingAs($this->admin, ['api']);
        setPermissionsTeamId($this->admin->company_id);
        return $this;
    }

    protected function actingAsManager(): static
    {
        Passport::actingAs($this->manager, ['api']);
        setPermissionsTeamId($this->manager->company_id);
        return $this;
    }

    protected function actingAsUser(?User $user = null): static
    {
        $user ??= $this->user;
        Passport::actingAs($user, ['api']);
        setPermissionsTeamId($user->company_id);
        return $this;
    }
}
