<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Company;

use App\Actions\Company\StoreProductAction;
use App\DTOs\Company\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

final class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $products = Product::where('company_id', $user->userable_id)->get();

        return sendSuccessResponse(
            message: 'تم جلب قائمة المنتجات بنجاح',
            data: $products
        );
    }

    public function store(
        StoreProductRequest $request,
        StoreProductAction $action
    ): JsonResponse {
        $dto = ProductDTO::fromRequest($request);
        $product = $action->execute($dto);

        return sendSuccessResponse(
            message: 'تم إضافة المنتج بنجاح',
            data: $product,
            status: 201
        );
    }

    public function show(Product $product): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($product->company_id !== $user->userable_id) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذا المنتج'], 403);
        }

        return sendSuccessResponse(
            message: 'تم جلب تفاصيل المنتج بنجاح',
            data: $product
        );
    }

    public function destroy(Product $product): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($product->company_id !== $user->userable_id) {
            return response()->json(['message' => 'غير مصرح لك بحذف هذا المنتج'], 403);
        }

        $product->delete();

        return sendSuccessResponse(
            message: 'تم حذف المنتج بنجاح'
        );
    }
}
