<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\JsonResponse;
use Payment\Services\Interfaces\InvoiceServiceInterface;

readonly class GetInvoiceController
{
    /**
     * Initialize the controller with the invoice service.
     *
     * @param \Payment\Services\Interfaces\InvoiceServiceInterface $invoiceService
     */
    public function __construct(private InvoiceServiceInterface $invoiceService)
    {
    }

    /**
     * Retrieve a single invoice by ID.
     *
     * @param int $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(int $invoice): JsonResponse
    {
        return response()->json($this->invoiceService->getInvoiceById($invoice));
    }
}
