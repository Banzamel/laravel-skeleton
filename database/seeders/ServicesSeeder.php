<?php

namespace Database\Seeders;

use Payment\Models\Invoice;
use Payment\Models\Proforma;
use Payment\Models\Service;
use Administration\Models\Company;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Seed test services and a sample invoice for the default company.
     */
    public function run(): void
    {
        $company = Company::where('slug', 'acme-corp')->firstOrFail();

        // Create test service
        $service = Service::withoutGlobalScopes()->firstOrCreate(
            ['name' => 'Basic Service', 'company_id' => $company->id],
            [
                'limit' => 10,
                'description' => 'Basic service description',
                'price' => 99.99,
            ]
        );

        // Create sample invoice with service (uses Observer for invoice_number and currency)
        $existingProforma = Proforma::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->where('billing_name', 'Acme Corporation')
            ->first();

        if (!$existingProforma) {
            $proforma = Proforma::withoutGlobalScopes()->create([
                'company_id' => $company->id,
                'due_date' => now()->addDays(30),
                'total_amount' => $service->price,
                'currency' => Invoice::currencyForCountry($company->country),
                'status' => 'pending',
                'billing_name' => 'Acme Corporation',
                'address' => '123 Test St',
                'city' => 'Warsaw',
                'country' => $company->country,
                'is_proforma' => true,
                'proforma_number' => 'FP/001/01/2026',
            ]);

            $proforma->services()->attach($service->id, [
                'quantity' => 1,
                'discount' => 0,
                'price' => $service->price,
            ]);
        }

        // Extend company expiration
        $company->update(['expired_at' => now()->addDays(30)]);
    }
}
