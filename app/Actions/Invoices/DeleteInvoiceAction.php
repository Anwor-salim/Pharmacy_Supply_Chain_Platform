<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\Models\Invoice;

final class DeleteInvoiceAction
{
    public function execute(Invoice $invoice): bool
    {
        return (bool) $invoice->delete();
    }
}
