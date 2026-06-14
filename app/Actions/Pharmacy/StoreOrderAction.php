<?php

declare(strict_types=1);

namespace App\Actions\Pharmacy;

use App\DTOs\Pharmacy\OrderDTO;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

final class StoreOrderAction
{
    public function execute(OrderDTO $dto): Order
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->userable_type !== \App\Models\Pharmacy::class) {
            throw new Exception("عذراً، فقط الصيدليات يمكنها إنشاء طلبات جديدة.");
        }

        $pharmacyId = $user->userable_id;

        return DB::transaction(function () use ($dto, $pharmacyId) {
            $order = Order::create([
                'company_id'  => $dto->companyId,
                'pharmacy_id' => $pharmacyId,
                'notes'       => $dto->notes,
                'status'      => 'pending',
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Verify product belongs to the company
                if ($product->company_id !== $dto->companyId) {
                    throw new Exception("المنتج {$product->name} لا ينتمي للشركة المختارة.");
                }

                // Check stock
                if ($product->stock_quantity < $item['quantity']) {
                    throw new Exception("الكمية المطلوبة للمنتج {$product->name} غير متوفرة.");
                }

                $lineTotal = $product->price * $item['quantity'];
                $totalAmount += $lineTotal;

                $order->items()->create([
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'product_sku'  => $product->batch_number, // using batch number as sku if needed
                    'quantity'     => $item['quantity'],
                    'unit_price'   => $product->price,
                    'total_price'  => $lineTotal,
                ]);

                // Deduct stock
                $product->decrement('stock_quantity', $item['quantity']);
            }

            $order->update(['total_amount' => $totalAmount]);

            return $order->load('items');
        });
    }
}
