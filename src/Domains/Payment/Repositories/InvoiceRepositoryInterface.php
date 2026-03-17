<?php

namespace Payment\Repositories;

use Payment\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

interface InvoiceRepositoryInterface
{
    public function findAll(array $params = []): LengthAwarePaginator;
    public function findOrFail(int $id, array $with = []): Invoice;
    public function update(Invoice $invoice, array $data): Invoice;
}
