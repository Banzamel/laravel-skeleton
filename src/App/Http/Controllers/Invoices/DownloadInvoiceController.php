<?php

namespace App\Http\Controllers\Invoices;

use Illuminate\Http\Request;
use Payment\Services\Interfaces\InvoiceServiceInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

readonly class DownloadInvoiceController
{
    /**
     * Create a new DownloadInvoiceController instance.
     *
     * @param \Payment\Services\Interfaces\InvoiceServiceInterface $invoiceService
     */
    public function __construct(
        private InvoiceServiceInterface $invoiceService
    ) {}

    /**
     * Download the specified invoice as a file.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $invoice
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function __invoke(Request $request, int $invoice): BinaryFileResponse
    {
        $filePath = $this->invoiceService->getInvoiceFile($invoice);

        return response()->file($filePath);
    }
}
