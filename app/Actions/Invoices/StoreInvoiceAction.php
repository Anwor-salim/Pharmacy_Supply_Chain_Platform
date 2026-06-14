<?php

declare(strict_types=1);

namespace App\Actions\Invoices;

use App\DTOs\Invoices\StoreInvoiceDTO;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

final class StoreInvoiceAction
{
    public function execute(StoreInvoiceDTO $dto): Invoice
    {
        return DB::transaction(function () use ($dto) {
            $subtotal = 0;
            $discountTotal = 0;
            $taxTotal = 0;

            $itemsData = [];
            foreach ($dto->items as $itemDto) {
                $itemSubtotal = $itemDto->quantity * $itemDto->unit_price;
                $itemDiscount = $itemSubtotal * ($itemDto->discount / 100);
                $itemTotal = ($itemSubtotal - $itemDiscount) * (1 + ($itemDto->tax_percent / 100));

                $subtotal += $itemSubtotal;
                $discountTotal += $itemDiscount;
                $taxTotal += ($itemSubtotal - $itemDiscount) * ($itemDto->tax_percent / 100);

                $itemsData[] = [
                    'product_id'   => $itemDto->product_id,
                    'product_name' => $itemDto->product_name,
                    'quantity'     => $itemDto->quantity,
                    'unit_price'   => $itemDto->unit_price,
                    'discount'     => $itemDto->discount, // percentage
                    'tax_percent'  => $itemDto->tax_percent,
                    'total'        => $itemTotal,
                ];
            }

            $totalAmount = $subtotal - $discountTotal + $taxTotal;

            $status = $dto->is_confirmed ? ($dto->type === 'cash' ? 'paid' : 'unpaid') : 'draft';
            if ($dto->is_confirmed && $dto->type === 'cash') {
                $status = 'paid';
            } elseif ($dto->is_confirmed && $dto->type === 'credit') {
                $status = 'unpaid';
            }

            $invoice = Invoice::create([
                'company_id'     => auth()->user()->company->id,
                'invoice_number' => $dto->invoice_number ?: 'INV-' . strtoupper(uniqid()),
                'type'           => $dto->type,
                'customer_id'    => $dto->customer_id,
                'status'         => $status,
                'subtotal'       => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total'      => $taxTotal,
                'total_amount'   => $totalAmount,
                'currency'       => $dto->currency,
                'notes'          => $dto->notes,
                'created_by'     => Auth::id(),
            ]);

            foreach ($itemsData as $item) {
                $invoice->items()->create($item);
            }

            return $invoice;
        });
    }
}
