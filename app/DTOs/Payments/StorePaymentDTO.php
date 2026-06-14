<?php

declare(strict_types=1);

namespace App\DTOs\Payments;

use App\Http\Requests\Payments\StorePaymentRequest;

final class StorePaymentDTO
{
    public function __construct(
        public readonly int $invoice_id,
        public readonly float $amount,
        public readonly string $method,
        public readonly ?string $reference_number,
        public readonly ?string $paid_at,
    ) {}

    public static function fromRequest(StorePaymentRequest $request, int $invoiceId): self
    {
        return new self(
            invoice_id:       $invoiceId,
            amount:           (float) $request->validated('amount'),
            method:           $request->validated('method'),
            reference_number: $request->validated('reference_number'),
            paid_at:          $request->validated('paid_at'),
        );
    }
}
