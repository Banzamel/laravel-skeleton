<?php

namespace Database\Seeders;

use Payment\Models\Service;
use Administration\Models\Company;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('slug', 'default-company')->firstOrFail();

        Service::withoutGlobalScopes()->firstOrCreate(
            ['name' => 'Basic Service', 'company_id' => $company->id],
            [
                'limit' => 10,
                'description' => 'Basic service description',
                'price' => 99.99,
            ]
        );

        $company->update(['expired_at' => now()->addDays(30)]);
    }
}
