<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Company;

use App\Actions\Orders\UpdateOrderStatusAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class CompanyOrdersUpdateStatusController extends Controller
{
    public function __invoke(Request $request, int $orderId): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
        ]);

        /** @var \App\Models\User $user */
        $user    = $request->user();
        $company = $user->userable;

        $order = app(UpdateOrderStatusAction::class)($company, $orderId, $request->status);

        return sendSuccessResponse(
            message: 'تم تحديث حالة الطلب بنجاح',
            data: $order,
        );
    }
}
