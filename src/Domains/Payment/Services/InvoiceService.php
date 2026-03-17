<?php

namespace Payment\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Payment\Dtos\InvoiceUpdateDto;
use Payment\Events\InvoiceUpdatedEvent;
use Payment\Models\Invoice;
use Payment\Repositories\InvoiceRepositoryInterface;
use Payment\Services\Interfaces\InvoiceServiceInterface;
use Shared\Exceptions\ApiJsonException;
use Illuminate\Support\Facades\Storage;

readonly class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(
        private InvoiceRepositoryInterface $invoiceRepository,
    ) {}

    /**
     * Get all confirmed invoices for the company (paginated).
     *
     * @param array<string, mixed> $params
     * @return LengthAwarePaginator
     */
    public function getAllInvoices(array $params = []): LengthAwarePaginator
    {
        return $this->invoiceRepository->findAll($params);
    }

    /**
     * Get a confirmed invoice by ID with relations.
     *
     * @param int $invoiceId
     * @return Invoice
     */
    public function getInvoiceById(int $invoiceId): Invoice
    {
        return $this->invoiceRepository->findOrFail($invoiceId);
    }

    /**
     * Update a confirmed invoice (status change only).
     *
     * @param int $invoiceId
     * @param InvoiceUpdateDto $dto
     * @return Invoice
     */
    public function updateInvoice(int $invoiceId, InvoiceUpdateDto $dto): Invoice
    {
        $invoice = $this->invoiceRepository->findOrFail($invoiceId);
        $invoice = $this->invoiceRepository->update($invoice, $dto->toArray());
        $invoice->load(['services']);

        event(new InvoiceUpdatedEvent($invoice, auth()->user()));

        return $invoice;
    }

    /**
     * Returns the full storage path to the invoice PDF file.
     *
     * @param int $invoiceId
     * @return string
     * @throws ApiJsonException
     */
    public function getInvoiceFile(int $invoiceId): string
    {
        $invoice = $this->invoiceRepository->findOrFail($invoiceId, []);

        $companyId = auth()->user()->company_id;
        $path = "invoices/{$companyId}/{$invoice->invoice_number}.pdf";

        if (!Storage::disk('local')->exists($path)) {
            throw new ApiJsonException('Invoice file not found', 404);
        }

        return Storage::disk('local')->path($path);
    }
}
