<?php

namespace Payment\Observers;

use Payment\Models\Service;
use Shared\Exceptions\ApiJsonException;

class ServiceObserver
{
    /**
     * Prevent deletion of a service that is used in existing invoices.
     *
     * @param Service $service
     * @return void
     * @throws ApiJsonException
     */
    public function deleting(Service $service): void
    {
        $invoiceCount = $service->belongsToMany(
            \Payment\Models\Invoice::class,
            'mgr_invoice_services',
            'service_id',
            'invoice_id'
        )->withoutGlobalScopes()->count();

        if ($invoiceCount > 0) {
            throw new ApiJsonException('Cannot delete a service that is used in existing invoices', 422);
        }
    }
}
