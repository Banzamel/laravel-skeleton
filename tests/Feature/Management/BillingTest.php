<?php

namespace Tests\Feature\Management;

use Tests\Feature\ApiTestCase;

class BillingTest extends ApiTestCase
{
    // ── GET ──

    public function test_admin_can_get_billing(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/management/billing');

        $response->assertOk();
    }

    public function test_teacher_cannot_get_billing(): void
    {
        $this->actingAsTeacher();

        $response = $this->getJson('/api/management/billing');

        $response->assertForbidden();
    }

    public function test_unauthenticated_cannot_get_billing(): void
    {
        $response = $this->getJson('/api/management/billing');

        $response->assertUnauthorized();
    }

    // ── UPDATE ──

    public function test_admin_can_update_billing(): void
    {
        $this->actingAsAdmin();

        $response = $this->putJson('/api/management/billing', [
            'tax_id' => 'PL1234567890',
            'billing_address' => 'ul. Szkolna 15',
            'billing_city' => 'Warszawa',
            'billing_country' => 'PL',
            'bank_name' => 'PKO BP',
            'bank_account' => 'PL61109010140000071219812874',
            'proforma_format' => 'FP/{NNN}/{MM}/{YYYY}',
            'invoice_format' => 'FV/{NNN}/{MM}/{YYYY}',
        ]);

        $response->assertOk();
    }

    public function test_admin_can_partially_update_billing(): void
    {
        $this->actingAsAdmin();

        $response = $this->putJson('/api/management/billing', [
            'tax_id' => 'PL9999999999',
        ]);

        $response->assertOk();
    }

    public function test_teacher_cannot_update_billing(): void
    {
        $this->actingAsTeacher();

        $response = $this->putJson('/api/management/billing', [
            'tax_id' => 'PL0000000000',
        ]);

        $response->assertForbidden();
    }
}
