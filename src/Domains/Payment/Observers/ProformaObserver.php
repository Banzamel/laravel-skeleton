<?php

namespace Payment\Observers;

use Payment\Models\Proforma;

class ProformaObserver
{
    /**
     * Auto-generate proforma number and set currency before creation.
     *
     * @param Proforma $proforma
     * @return void
     */
    public function creating(Proforma $proforma): void
    {
        $proforma->proforma_number = $this->generateProformaNumber();
        $proforma->is_proforma = true;

        $company = \Administration\Models\Company::find($proforma->company_id);
        $proforma->currency = Proforma::currencyForCountry($company->country ?? 'PL');
    }

    /**
     * Generate invoice number when proforma is confirmed.
     *
     * @param Proforma $proforma
     * @return void
     */
    public function updating(Proforma $proforma): void
    {
        if ($proforma->isDirty('is_proforma') && $proforma->is_proforma === false) {
            $proforma->invoice_number = $this->generateInvoiceNumber();
        }
    }

    /**
     * Generate a sequential proforma number (FP/001/MM/YYYY).
     *
     * @return string
     */
    private function generateProformaNumber(): string
    {
        $month = now()->format('m');
        $year = now()->format('Y');

        $count = Proforma::withoutGlobalScopes()
            ->withTrashed()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('proforma_number')
            ->count();

        $next = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "FP/{$next}/{$month}/{$year}";
    }

    /**
     * Generate a sequential invoice number (FV/001/MM/YYYY).
     *
     * @return string
     */
    private function generateInvoiceNumber(): string
    {
        $month = now()->format('m');
        $year = now()->format('Y');

        $count = Proforma::withoutGlobalScopes()
            ->withTrashed()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->whereNotNull('invoice_number')
            ->count();

        $next = str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        return "FV/{$next}/{$month}/{$year}";
    }
}
