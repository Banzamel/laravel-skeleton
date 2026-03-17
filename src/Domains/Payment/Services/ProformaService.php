<?php

namespace Payment\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Payment\Dtos\ProformaCreateDto;
use Payment\Dtos\ProformaUpdateDto;
use Payment\Events\ProformaConfirmedEvent;
use Payment\Events\ProformaCreatedEvent;
use Payment\Events\ProformaDeletedEvent;
use Payment\Events\ProformaRestoredEvent;
use Payment\Events\ProformaUpdatedEvent;
use Payment\Models\Proforma;
use Payment\Repositories\ProformaRepositoryInterface;
use Payment\Repositories\ServiceRepositoryInterface;
use Payment\Services\Interfaces\ProformaServiceInterface;

readonly class ProformaService implements ProformaServiceInterface
{
    public function __construct(
        private ProformaRepositoryInterface $proformaRepository,
        private ServiceRepositoryInterface $serviceRepository,
    ) {}

    /**
     * Get all proformas for the company (paginated).
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getAllProformas(array $params = []): LengthAwarePaginator
    {
        return $this->proformaRepository->findAll($params);
    }

    /**
     * Get a proforma by ID with relations.
     *
     * @param int $proformaId
     * @return Proforma
     */
    public function getProformaById(int $proformaId): Proforma
    {
        return $this->proformaRepository->findOrFail($proformaId);
    }

    /**
     * Create a new proforma with attached services.
     *
     * @param ProformaCreateDto $dto
     * @return Proforma
     */
    public function createProforma(ProformaCreateDto $dto): Proforma
    {
        $servicePivotData = $this->buildServicePivotData($dto->getServices());
        $totalAmount = $this->calculateTotalAmount($servicePivotData);

        $proforma = $this->proformaRepository->create(array_merge($dto->toArray(), [
            'total_amount' => $totalAmount,
        ]));

        $proforma->services()->attach($this->formatPivotForAttach($servicePivotData));
        $proforma->load(['services']);

        event(new ProformaCreatedEvent($proforma, auth()->user()));

        return $proforma;
    }

    /**
     * Update a proforma. Recalculates total if services are changed.
     *
     * @param int $proformaId
     * @param ProformaUpdateDto $dto
     * @return Proforma
     */
    public function updateProforma(int $proformaId, ProformaUpdateDto $dto): Proforma
    {
        $proforma = $this->proformaRepository->findOrFail($proformaId);

        $updateData = $dto->toArray();

        if ($dto->getServices() !== null) {
            $servicePivotData = $this->buildServicePivotData($dto->getServices());
            $updateData['total_amount'] = $this->calculateTotalAmount($servicePivotData);

            $proforma->services()->sync($this->formatPivotForAttach($servicePivotData));
        }

        $proforma = $this->proformaRepository->update($proforma, $updateData);
        $proforma->load(['services']);

        event(new ProformaUpdatedEvent($proforma, auth()->user()));

        return $proforma;
    }

    /**
     * Delete a proforma (soft delete).
     *
     * @param int $proformaId
     * @return bool
     */
    public function deleteProforma(int $proformaId): bool
    {
        $proforma = $this->proformaRepository->findOrFail($proformaId);

        event(new ProformaDeletedEvent($proforma, auth()->user()));

        return $this->proformaRepository->delete($proforma);
    }

    /**
     * Restore a soft-deleted proforma.
     *
     * @param int $proformaId
     * @return Proforma
     */
    public function restoreProforma(int $proformaId): Proforma
    {
        $proforma = $this->proformaRepository->restore($proformaId);

        event(new ProformaRestoredEvent($proforma, auth()->user()));

        return $proforma;
    }

    /**
     * Confirm a proforma — generates invoice number and converts to final invoice.
     *
     * @param int $proformaId
     * @return Proforma
     */
    public function confirmProforma(int $proformaId): Proforma
    {
        $proforma = $this->proformaRepository->findOrFail($proformaId);

        $proforma = $this->proformaRepository->update($proforma, [
            'is_proforma' => false,
        ]);

        $proforma->load(['services']);

        event(new ProformaConfirmedEvent($proforma, auth()->user()));

        return $proforma;
    }

    /**
     * Build pivot data by looking up each service price.
     *
     * @param array<int, array{service_id: int, quantity: int, discount?: float}> $services
     * @return array<int, array{service_id: int, quantity: int, discount: float, price: float}>
     */
    private function buildServicePivotData(array $services): array
    {
        $pivotData = [];

        foreach ($services as $item) {
            $service = $this->serviceRepository->findOrFail($item['service_id']);

            $pivotData[] = [
                'service_id' => $service->id,
                'quantity' => $item['quantity'],
                'discount' => (float) ($item['discount'] ?? 0),
                'price' => (float) $service->price,
            ];
        }

        return $pivotData;
    }

    /**
     * Calculate total amount from service lines (net: price * quantity - discount).
     *
     * @param array<int, array{price: float, quantity: int, discount: float}> $pivotData
     * @return float
     */
    private function calculateTotalAmount(array $pivotData): float
    {
        $total = 0;

        foreach ($pivotData as $line) {
            $lineTotal = ($line['price'] * $line['quantity']) - $line['discount'];
            $total += max($lineTotal, 0);
        }

        return round($total, 2);
    }

    /**
     * Format pivot data for Eloquent attach/sync.
     *
     * @param array<int, array{service_id: int, quantity: int, discount: float, price: float}> $pivotData
     * @return array<int, array{quantity: int, discount: float, price: float}>
     */
    private function formatPivotForAttach(array $pivotData): array
    {
        $formatted = [];

        foreach ($pivotData as $line) {
            $formatted[$line['service_id']] = [
                'quantity' => $line['quantity'],
                'discount' => $line['discount'],
                'price' => $line['price'],
            ];
        }

        return $formatted;
    }
}
