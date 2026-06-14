<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\DTOs\Company\ProductDTO;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

final class StoreProductAction
{
    public function execute(ProductDTO $dto): Product
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $imagePath = null;
        if ($dto->image) {
            $imagePath = $dto->image->store('products', 'public');
        }

        return Product::create([
            'company_id'      => $user->userable_id,
            'name'            => $dto->name,
            'scientific_name' => $dto->scientific_name,
            'description'     => $dto->description,
            'strength'        => $dto->strength,
            'dosage_form'     => $dto->dosage_form,
            'production_date' => $dto->production_date,
            'expiry_date'     => $dto->expiry_date,
            'batch_number'    => $dto->batch_number,
            'price'           => $dto->price,
            'stock_quantity'  => $dto->stock_quantity,
            'status'          => $dto->status,
            'image_path'      => $imagePath,
        ]);
    }
}
