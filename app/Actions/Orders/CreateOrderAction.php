<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\DTOs\Orders\CreateOrderDTO;
use App\Models\Company;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

final class CreateOrderAction
{
    public function __invoke(Company $company, CreateOrderDTO $dto): Order
    {
        return DB::transaction(function () use ($company, $dto) {
            $order = $company->orders()->create([
                'pharmacy_id'  => $dto->pharmacyId,
                'notes'        => $dto->notes,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($dto->items as $item) {
                $lineTotal = $item['quantity'] * $item['unit_price'];
                $total += $lineTotal;

                $order->items()->create([
                    'product_name' => $item['product_name'],
                    'product_sku'  => $item['product_sku'] ?? null,
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $item['unit_price'],
                    'total_price'  => $lineTotal,
                ]);
            }

            $order->update(['total_amount' => $total]);

            return $order->load(['pharmacy', 'items']);
        });
    }
}
