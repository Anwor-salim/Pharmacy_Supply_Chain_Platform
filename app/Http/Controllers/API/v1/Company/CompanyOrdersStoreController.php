<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Company;

use App\Actions\Orders\CreateOrderAction;
use App\DTOs\Orders\CreateOrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\CreateOrderRequest;
use Illuminate\Http\JsonResponse;

final class CompanyOrdersStoreController extends Controller
{
    public function __invoke(CreateOrderRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user    = $request->user();
        $company = $user->userable;

        $dto   = CreateOrderDTO::fromRequest($request);
        $order = app(CreateOrderAction::class)($company, $dto);

        return sendSuccessResponse(
            message: 'تم إنشاء الطلب بنجاح',
            data: $order,
            statusCode: 201,
        );
    }
}
