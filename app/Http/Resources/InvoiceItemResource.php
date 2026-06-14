<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\InvoiceItem
 */
final class InvoiceItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'product_id'   => $this->product_id,
            'product_name' => $this->product_name,
            'quantity'     => $this->quantity,
            'unit_price'   => $this->unit_price,
            'discount'     => $this->discount,
            'tax_percent'  => $this->tax_percent,
            'total'        => $this->total,
        ];
    }
}
