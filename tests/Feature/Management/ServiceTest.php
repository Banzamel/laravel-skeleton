<?php

namespace Tests\Feature\Management;

use Database\Seeders\ServicesSeeder;
use Tests\Feature\ApiTestCase;

class ServiceTest extends ApiTestCase
{
    // ── LIST ──

    public function test_admin_can_list_services(): void
    {
        $this->actingAsAdmin();

        $response = $this->getJson('/api/management/services');

        $response->assertOk();
    }

    public function test_teacher_cannot_list_services(): void
    {
        $this->actingAsTeacher();

        $response = $this->getJson('/api/management/services');

        $response->assertForbidden();
    }

    // ── CREATE ──

    public function test_admin_can_create_service(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/services', [
            'name' => 'Premium Package',
            'limit' => 20,
            'description' => '20 lessons per month',
            'price' => 299.99,
        ]);

        $response->assertCreated()
            ->assertJsonPath('name', 'Premium Package');
    }

    public function test_create_service_with_minimal_data(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/services', [
            'name' => 'Basic',
        ]);

        $response->assertCreated();
    }

    public function test_create_service_fails_without_name(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/management/services', [
            'price' => 100,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    // ── SHOW ──

    public function test_admin_can_get_service(): void
    {
        $this->actingAsAdmin();

        $created = $this->postJson('/api/management/services', [
            'name' => 'Show Service',
            'price' => 50,
        ]);

        $serviceId = $created->json('id');

        $response = $this->getJson("/api/management/services/{$serviceId}");

        $response->assertOk()
            ->assertJsonPath('name', 'Show Service');
    }

    // ── UPDATE ──

    public function test_admin_can_update_service(): void
    {
        $this->actingAsAdmin();

        $created = $this->postJson('/api/management/services', [
            'name' => 'Before Update',
            'price' => 100,
        ]);

        $serviceId = $created->json('id');

        $response = $this->putJson("/api/management/services/{$serviceId}", [
            'name' => 'After Update',
            'price' => 150,
        ]);

        $response->assertOk()
            ->assertJsonPath('name', 'After Update');
    }

    // ── DELETE ──

    public function test_admin_can_delete_unused_service(): void
    {
        $this->actingAsAdmin();

        $created = $this->postJson('/api/management/services', [
            'name' => 'Deletable',
            'price' => 10,
        ]);

        $serviceId = $created->json('id');

        $response = $this->deleteJson("/api/management/services/{$serviceId}");

        $response->assertNoContent();
    }

    public function test_cannot_delete_service_used_in_invoice(): void
    {
        $this->actingAsAdmin();

        // Create service and proforma that uses the service
        $service = $this->postJson('/api/management/services', [
            'name' => 'Used Service',
            'price' => 100,
        ]);
        $service->assertCreated();
        $serviceId = $service->json('id');

        $proforma = $this->postJson('/api/management/proformas', [
            'due_date' => now()->addMonth()->format('Y-m-d'),
            'billing_name' => 'Test',
            'services' => [
                ['service_id' => $serviceId, 'quantity' => 1, 'discount' => 0],
            ],
        ]);
        $proforma->assertCreated();

        $response = $this->deleteJson("/api/management/services/{$serviceId}");

        $response->assertStatus(422);
    }
}
