<?php

declare(strict_types=1);

namespace App\DTOs\Pharmacy;

use App\Http\Requests\Pharmacy\StoreOrderRequest;

final class OrderDTO
{
    public function __construct(
        public readonly int $companyId,
        public readonly array $items,
        public readonly ?string $notes,
    ) {}

    public static function fromRequest(StoreOrderRequest $request): self
    {
        return new self(
            companyId: (int) $request->validated('company_id'),
            items: $request->validated('items'),
            notes: $request->validated('notes'),
        );
    }
}
