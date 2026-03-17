<?php

namespace Payment\Services\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Payment\Dtos\InvoiceUpdateDto;
use Payment\Models\Invoice;

interface InvoiceServiceInterface
{
    /**
     * Get all confirmed invoices for the company (paginated).
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getAllInvoices(array $params = []): LengthAwarePaginator;

    /**
     * Get a confirmed invoice by ID.
     *
     * @param int $invoiceId
     * @return Invoice
     */
    public function getInvoiceById(int $invoiceId): Invoice;

    /**
     * Update a confirmed invoice (status only).
     *
     * @param int $invoiceId
     * @param InvoiceUpdateDto $dto
     * @return Invoice
     */
    public function updateInvoice(int $invoiceId, InvoiceUpdateDto $dto): Invoice;

    /**
     * Get the file path to the invoice PDF.
     *
     * @param int $invoiceId
     * @return string
     */
    public function getInvoiceFile(int $invoiceId): string;
}
