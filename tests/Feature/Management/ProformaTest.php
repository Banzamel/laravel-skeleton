<?php

namespace Tests\Feature\Management;

use Tests\Feature\ApiTestCase;

class ProformaTest extends ApiTestCase
{
    private int $serviceId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();

        $service = $this->postJson('/api/management/services', [
            'name' => 'Test Service',
            'price' => 100.00,
        ]);
        $this->serviceId = $service->json('id');
    }

    private function createProforma(): int
    {
        $response = $this->postJson('/api/management/proformas', [
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'billing_name' => 'Jan Kowalski',
            'address' => 'ul. Testowa 1',
            'city' => 'Warszawa',
            'country' => 'PL',
            'services' => [
                [
                    'service_id' => $this->serviceId,
                    'quantity' => 2,
                    'discount' => 0,
                ],
            ],
        ]);

        return $response->json('id');
    }

    // ── LIST ──

    public function test_admin_can_list_proformas(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/management/proformas');

        $response->assertOk();
    }

    public function test_teacher_cannot_list_proformas(): void
    {
        $this->actingAsTeacher();

        $response = $this->getJson('/api/management/proformas');

        $response->assertForbidden();
    }

    // ── CREATE ──

    public function test_admin_can_create_proforma(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/proformas', [
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'billing_name' => 'Jan Kowalski',
            'address' => 'ul. Testowa 1',
            'city' => 'Warszawa',
            'country' => 'PL',
            'services' => [
                [
                    'service_id' => $this->serviceId,
                    'quantity' => 1,
                    'discount' => 0,
                ],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('billing_name', 'Jan Kowalski');
    }

    public function test_create_proforma_fails_without_services(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/proformas', [
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'billing_name' => 'Test',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['services']);
    }

    public function test_create_proforma_fails_without_billing_name(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/proformas', [
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'services' => [
                ['service_id' => $this->serviceId, 'quantity' => 1, 'discount' => 0],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['billing_name']);
    }

    public function test_create_proforma_fails_with_past_due_date(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/proformas', [
            'due_date' => '2020-01-01',
            'billing_name' => 'Test',
            'services' => [
                ['service_id' => $this->serviceId, 'quantity' => 1, 'discount' => 0],
            ],
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['due_date']);
    }

    // ── SHOW ──

    public function test_admin_can_get_proforma(): void
    {
        $this->actingAsAdmin();
        $proformaId = $this->createProforma();

        $response = $this->getJson("/api/management/proformas/{$proformaId}");

        $response->assertOk();
    }

    // ── UPDATE ──

    public function test_admin_can_update_proforma(): void
    {
        $this->actingAsAdmin();
        $proformaId = $this->createProforma();

        $response = $this->putJson("/api/management/proformas/{$proformaId}", [
            'billing_name' => 'Updated Name',
        ]);

        $response->assertOk();
    }

    // ── DELETE & RESTORE ──

    public function test_admin_can_delete_proforma(): void
    {
        $this->actingAsAdmin();
        $proformaId = $this->createProforma();

        $response = $this->deleteJson("/api/management/proformas/{$proformaId}");

        $response->assertNoContent();
    }

    public function test_admin_can_restore_deleted_proforma(): void
    {
        $this->actingAsAdmin();
        $proformaId = $this->createProforma();

        $this->deleteJson("/api/management/proformas/{$proformaId}");

        $response = $this->postJson("/api/management/proformas/{$proformaId}/restore");

        $response->assertOk();
    }

    // ── CONFIRM ──

    public function test_admin_can_confirm_proforma(): void
    {
        $this->actingAsAdmin();
        $proformaId = $this->createProforma();

        $response = $this->postJson("/api/management/proformas/{$proformaId}/confirm");

        $response->assertOk();
    }

    public function test_proforma_generates_invoice_number_on_confirm(): void
    {
        $this->actingAsAdmin();
        $proformaId = $this->createProforma();

        $this->postJson("/api/management/proformas/{$proformaId}/confirm");

        // After confirm, the proforma becomes an invoice
        $invoices = $this->getJson('/api/management/invoices');
        $invoices->assertOk();

        $data = $invoices->json('data');
        $this->assertNotEmpty($data);
        $this->assertNotNull($data[0]['invoice_number']);
    }
}
