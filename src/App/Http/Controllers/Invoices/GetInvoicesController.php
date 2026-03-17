<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Payment\Services\Interfaces\InvoiceServiceInterface;

readonly class GetInvoicesController
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
     * Retrieve all invoices for the authenticated user's company (paginated).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($this->invoiceService->getAllInvoices($request->query()));
    }
}
