<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Invoice
 */
final class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'company_id'          => $this->company_id,
            'invoice_number'      => $this->invoice_number,
            'type'                => $this->type,
            'status'              => $this->status,
            'subtotal'            => $this->subtotal,
            'discount_total'      => $this->discount_total,
            'tax_total'           => $this->tax_total,
            'total_amount'        => $this->total_amount,
            'paid_amount'         => $this->paid_amount,
            'remaining_amount'    => $this->remaining_amount,
            'currency'            => $this->currency,
            'customer_id'         => $this->customer_id,
            'customer_name'       => $this->customer?->name,
            'customer_address'    => $this->customer?->address,
            'customer_phone'      => $this->customer?->phone,
            'notes'               => $this->notes,
            'original_invoice_id' => $this->original_invoice_id,
            'created_at'          => $this->created_at->format('Y-m-d H:i:s'),
            'items'               => InvoiceItemResource::collection($this->whenLoaded('items')),
            'payments'            => $this->whenLoaded('payments'),
        ];
    }
}
