<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

final class ProductController extends Controller
{
    public function index(Company $company): JsonResponse
    {
        $products = Product::where('company_id', $company->id)
            ->where('status', true)
            ->where('stock_quantity', '>', 0)
            ->get();

        return sendSuccessResponse(
            message: 'تم جلب منتجات الشركة بنجاح',
            data: $products
        );
    }

    public function show(Product $product): JsonResponse
    {
        return sendSuccessResponse(
            message: 'تم جلب تفاصيل المنتج بنجاح',
            data: $product
        );
    }
}
