<?php

declare(strict_types=1);

namespace App\DTOs\Invoices;

use App\Http\Requests\Invoices\StoreReturnInvoiceRequest;

final class StoreReturnInvoiceDTO
{
    public function __construct(
        public readonly ?string $invoice_number,
        public readonly int $original_invoice_id,
        public readonly float $total_amount,
    ) {}

    public static function fromRequest(StoreReturnInvoiceRequest $request): self
    {
        return new self(
            invoice_number:      $request->validated('invoice_number') ?: 'RET-' . strtoupper(uniqid()),
            original_invoice_id: (int) $request->validated('original_invoice_id'),
            total_amount:        (float) $request->validated('total_amount'),
        );
    }
}
