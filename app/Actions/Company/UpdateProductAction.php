<?php

declare(strict_types=1);

namespace App\Actions\Company;

use App\Http\Requests\Company\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

final class UpdateProductAction
{
    /**
     * Update an existing product.
     */
    public function execute(UpdateProductRequest $request, Product $product): Product
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ensure the product belongs to the authenticated company
        if ($product->company_id !== $user->userable_id) {
            abort(403, 'غير مصرح لك بتعديل هذا المنتج');
        }

        $data = $request->only([
            'name',
            'scientific_name',
            'description',
            'strength',
            'dosage_form',
            'production_date',
            'expiry_date',
            'batch_number',
            'price',
            'stock_quantity',
            'status',
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image_path'] = $imagePath;
        }

        $product->update($data);

        return $product;
    }
}
