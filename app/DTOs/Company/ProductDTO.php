<?php

declare(strict_types=1);

namespace App\DTOs\Company;

use App\Http\Requests\Company\StoreProductRequest;

final class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $scientific_name,
        public readonly ?string $description,
        public readonly ?string $strength,
        public readonly ?string $dosage_form,
        public readonly string $production_date,
        public readonly string $expiry_date,
        public readonly ?string $batch_number,
        public readonly float $price,
        public readonly int $stock_quantity,
        public readonly bool $status,
        public readonly ?\Illuminate\Http\UploadedFile $image = null,
    ) {}

    public static function fromRequest(StoreProductRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            scientific_name: $request->validated('scientific_name'),
            description: $request->validated('description'),
            strength: $request->validated('strength'),
            dosage_form: $request->validated('dosage_form'),
            production_date: $request->validated('production_date'),
            expiry_date: $request->validated('expiry_date'),
            batch_number: $request->validated('batch_number'),
            price: (float) $request->validated('price'),
            stock_quantity: (int) $request->validated('stock_quantity'),
            status: (bool) ($request->validated('status') ?? true),
            image: $request->file('image'),
        );
    }
}
