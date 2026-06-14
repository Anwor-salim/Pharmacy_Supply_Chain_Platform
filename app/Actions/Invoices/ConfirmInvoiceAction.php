<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

final class ConfirmInvoiceAction
{
    public function execute(Invoice $invoice): Invoice
    {
        return DB::transaction(function () use ($invoice) {
            if ($invoice->status !== 'draft') {
                return $invoice;
            }

            $newStatus = $invoice->type === 'cash' ? 'paid' : 'unpaid';
            
            $invoice->update([
                'status'     => $newStatus,
                'updated_by' => Auth::id(),
            ]);

            // Stock Integration
            foreach ($invoice->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
                }
            }

            return $invoice;
        });
    }
}
