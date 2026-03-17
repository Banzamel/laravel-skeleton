<?php

namespace Tests\Feature\Management;

use Tests\Feature\ApiTestCase;

class InvoiceTest extends ApiTestCase
{
    private int $invoiceId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAsAdmin();

        // Create service, proforma, then confirm to get an invoice
        $service = $this->postJson('/api/management/services', ['name' => 'Inv Service', 'price' => 50]);

        $proforma = $this->postJson('/api/management/proformas', [
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'billing_name' => 'Test Invoice',
            'services' => [
                ['service_id' => $service->json('id'), 'quantity' => 1, 'discount' => 0],
            ],
        ]);

        $this->postJson("/api/management/proformas/{$proforma->json('id')}/confirm");

        $invoices = $this->getJson('/api/management/invoices');
        $this->invoiceId = $invoices->json('data.0.id');
    }

    // ── LIST ──

    public function test_admin_can_list_invoices(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/management/invoices');

        $response->assertOk()
            ->assertJsonStructure(['current_page', 'data']);
    }

    public function test_teacher_cannot_list_invoices(): void
    {
        $this->actingAsTeacher();

        $response = $this->getJson('/api/management/invoices');

        $response->assertForbidden();
    }

    // ── SHOW ──

    public function test_admin_can_get_invoice(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson("/api/management/invoices/{$this->invoiceId}");

        $response->assertOk()
            ->assertJsonPath('id', $this->invoiceId);
    }

    // ── UPDATE STATUS ──

    public function test_admin_can_update_invoice_status(): void
    {
        $this->actingAsAdmin();

        $response = $this->putJson("/api/management/invoices/{$this->invoiceId}", [
            'status' => 'paid',
        ]);

        $response->assertOk();
    }

    // ── DOWNLOAD ──

    public function test_download_nonexistent_invoice_returns_404(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/management/invoices/9999/download');

        $response->assertNotFound();
    }
}
