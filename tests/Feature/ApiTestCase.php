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
    protected User $teacher;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionsSeeder::class);
        $this->seed(UsersCompanySeeder::class);

        $this->company = Company::where('slug', 'acme-corp')->first();
        DB::table('sec_companies')
            ->where('id', $this->company->id)
            ->update(['expired_at' => now()->addYear()]);
        $this->company->refresh();

        $this->admin = User::where('email', 'admin@example.com')->first();
        $this->teacher = User::where('email', 'user@example.com')->first();
    }

    protected function actingAsAdmin(): static
    {
        Passport::actingAs($this->admin, ['api']);
        setPermissionsTeamId($this->admin->company_id);
        return $this;
    }

    protected function actingAsTeacher(): static
    {
        Passport::actingAs($this->teacher, ['api']);
        setPermissionsTeamId($this->teacher->company_id);
        return $this;
    }

    protected function actingAsUser(User $user): static
    {
        Passport::actingAs($user, ['api']);
        setPermissionsTeamId($user->company_id);
        return $this;
    }
}
