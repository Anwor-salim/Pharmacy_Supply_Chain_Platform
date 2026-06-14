<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

use App\Http\Requests\Invoices\StoreInvoiceRequest;

final class StoreInvoiceDTO
{
    /**
     * @param InvoiceItemDTO[] $items
     */
    public function __construct(
        public readonly ?string $invoice_number,
        public readonly string $type,
        public readonly ?int $customer_id,
        public readonly array $items,
        public readonly ?string $notes = null,
        public readonly string $currency = 'USD',
        public readonly bool $is_confirmed = false,
    ) {}

    public static function fromRequest(StoreInvoiceRequest $request): self
    {
        $items = array_map(
            fn(array $item) => InvoiceItemDTO::fromArray($item),
            $request->validated('items')
        );

        return new self(
            invoice_number: $request->validated('invoice_number'),
            type:           $request->validated('type'),
            customer_id:    (int) $request->validated('customer_id') ?: null,
            items:          $items,
            notes:          $request->validated('notes'),
            currency:       $request->validated('currency', 'USD'),
            is_confirmed:   (bool) $request->validated('is_confirmed', false),
        );
    }
}
