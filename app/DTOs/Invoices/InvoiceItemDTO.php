<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

final class InvoiceItemDTO
{
    public function __construct(
        public readonly ?int $product_id,
        public readonly string $product_name,
        public readonly int $quantity,
        public readonly float $unit_price,
        public readonly float $discount = 0,
        public readonly float $tax_percent = 0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_id:   $data['product_id'] ?? null,
            product_name: $data['product_name'],
            quantity:     (int) $data['quantity'],
            unit_price:   (float) $data['unit_price'],
            discount:     (float) ($data['discount'] ?? 0),
            tax_percent:  (float) ($data['tax_percent'] ?? 0),
        );
    }
}
