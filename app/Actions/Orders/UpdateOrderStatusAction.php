<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Company;
use App\Models\Order;
use Illuminate\Validation\ValidationException;

final class UpdateOrderStatusAction
{
    public function __invoke(Company $company, int $orderId, string $status): Order
    {
        $order = $company->orders()->findOrFail($orderId);

        if ($order->status === 'cancelled' || $order->status === 'delivered') {
            throw ValidationException::withMessages([
                'status' => ['لا يمكن تغيير حالة الطلب بعد إلغائه أو تسليمه.'],
            ]);
        }

        $order->update([
            'status'       => $status,
            'delivered_at' => $status === 'delivered' ? now() : $order->delivered_at,
        ]);

        return $order->load(['pharmacy', 'items']);
    }
}
