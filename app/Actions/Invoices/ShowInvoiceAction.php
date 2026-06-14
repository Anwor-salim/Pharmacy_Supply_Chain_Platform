<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\Models\Invoice;

final class ShowInvoiceAction
{
    public function execute(Invoice $invoice): Invoice
    {
        return $invoice->load(['customer', 'items', 'payments', 'originalInvoice', 'returnInvoices']);
    }
}
