<?php

namespace Payment\Observers;

use Payment\Models\Invoice;
use Shared\Exceptions\ApiJsonException;

class InvoiceObserver
{
    /**
     * Prevent deletion of confirmed invoices.
     *
     * @param Invoice $invoice
     * @return void
     * @throws ApiJsonException
     */
    public function deleting(Invoice $invoice): void
    {
        if (!$invoice->is_proforma) {
            throw new ApiJsonException('Cannot delete a confirmed invoice', 403);
        }
    }
}
