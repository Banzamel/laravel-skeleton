<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\JsonResponse;
use Payment\Requests\InvoiceUpdateRequest;
use Payment\Services\Interfaces\InvoiceServiceInterface;

readonly class UpdateInvoiceController
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
     * Update the specified invoice.
     *
     * @param \Payment\Requests\InvoiceUpdateRequest $request
     * @param int $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(InvoiceUpdateRequest $request, int $invoice): JsonResponse
    {
        return response()->json($this->invoiceService->updateInvoice($invoice, $request->getDto()));
    }
}
