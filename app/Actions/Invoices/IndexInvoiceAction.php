<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Collection;

final class IndexInvoiceAction
{
    public function execute(
        ?string $type = null,
        ?string $status = null,
        ?int $customer_id = null
    ): Collection {
        $company = auth()->user()->company;
        $query = Invoice::query()->with(['items', 'payments'])
            ->where('company_id', $company ? $company->id : null);

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($customer_id) {
            $query->where('customer_id', $customer_id);
        }

        return $query->latest()->get();
    }
}
