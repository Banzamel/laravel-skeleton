<?php

namespace Payment\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Payment\Dtos\ProformaCreateDto;
use Payment\Dtos\ProformaUpdateDto;
use Payment\Models\Proforma;

interface ProformaServiceInterface
{
    /**
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getAllProformas(array $params = []): LengthAwarePaginator;

    /**
     * @param int $proformaId
     * @return Proforma
     */
    public function getProformaById(int $proformaId): Proforma;

    /**
     * @param ProformaCreateDto $dto
     * @return Proforma
     */
    public function createProforma(ProformaCreateDto $dto): Proforma;

    /**
     * @param int $proformaId
     * @param ProformaUpdateDto $dto
     * @return Proforma
     */
    public function updateProforma(int $proformaId, ProformaUpdateDto $dto): Proforma;

    /**
     * @param int $proformaId
     * @return bool
     */
    public function deleteProforma(int $proformaId): bool;

    /**
     * @param int $proformaId
     * @return Proforma
     */
    public function restoreProforma(int $proformaId): Proforma;

    /**
     * @param int $proformaId
     * @return Proforma
     */
    public function confirmProforma(int $proformaId): Proforma;
}
