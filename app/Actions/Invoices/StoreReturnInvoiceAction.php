<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\DTOs\Invoices\StoreReturnInvoiceDTO;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

final class StoreReturnInvoiceAction
{
    public function execute(StoreReturnInvoiceDTO $dto): Invoice
    {
        return DB::transaction(function () use ($dto) {
            $originalInvoice = Invoice::where('company_id', auth()->user()->company->id)
                ->findOrFail($dto->original_invoice_id);

            // In a real ERP, we might copy items from original. 
            // For now, we'll just create the return invoice header.
            
            return Invoice::create([
                'company_id'          => auth()->user()->company->id,
                'invoice_number'      => $dto->invoice_number,
                'type'                => 'return',
                'original_invoice_id' => $dto->original_invoice_id,
                'customer_id'         => $originalInvoice->customer_id,
                'total_amount'        => -$dto->total_amount, // Negative impact
                'status'              => 'paid', // Returns are usually settled immediately
                'currency'            => $originalInvoice->currency,
                'created_by'          => Auth::id(),
            ]);
        });
    }
}
