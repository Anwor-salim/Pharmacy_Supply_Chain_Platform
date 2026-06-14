<?php

declare(strict_types=1);

namespace App\DTOs\Orders;

use Illuminate\Http\Request;

final class CreateOrderDTO
{
    public function __construct(
        public readonly int $pharmacyId,
        public readonly ?string $notes,
        public readonly array $items, // [['product_name', 'quantity', 'unit_price', 'product_sku'?], ...]
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            pharmacyId: (int) $request->pharmacy_id,
            notes: $request->notes,
            items: $request->items ?? [],
        );
    }
}
