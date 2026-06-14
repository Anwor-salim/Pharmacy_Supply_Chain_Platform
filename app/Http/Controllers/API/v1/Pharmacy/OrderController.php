<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Pharmacy;

use App\Actions\Pharmacy\StoreOrderAction;
use App\DTOs\Pharmacy\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pharmacy\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

final class OrderController extends Controller
{
    public function index(): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $orders = Order::where('pharmacy_id', $user->userable_id)
            ->with(['company', 'pharmacy', 'items'])
            ->latest()
            ->get();

        return sendSuccessResponse(
            message: 'تم جلب قائمة طلباتكم بنجاح',
            data: $orders
        );
    }

    public function store(
        StoreOrderRequest $request,
        StoreOrderAction $action
    ): JsonResponse {
        try {
            $dto = OrderDTO::fromRequest($request);
            $order = $action->execute($dto);

            return sendSuccessResponse(
                message: 'تم إرسال الطلب بنجاح',
                data: $order,
                status: 201
            );
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(Order $order): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($order->pharmacy_id !== $user->userable_id) {
            return response()->json(['message' => 'غير مصرح لك بالوصول لهذا الطلب'], 403);
        }

        return sendSuccessResponse(
            message: 'تم جلب تفاصيل الطلب بنجاح',
            data: $order->load(['company', 'pharmacy', 'items'])
        );
    }
}
