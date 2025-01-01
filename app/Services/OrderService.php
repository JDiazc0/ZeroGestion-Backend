<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class OrderService
{

    /**
     * Create new order and add products
     * @param array $data
     * @return Order
     */
    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'client_id' => $data['client_id'],
                'delivery_date' => $data['delivery_date'],
                'status' => 'pending',
                'user_id' => auth()->id(),
            ]);

            if (isset($data['products']) && is_array($data['products'])) {
                $products = collect($data['products'])->map(function ($productData) {
                    return new OrderProduct([
                        'product_id' => $productData['product_id'],
                        'quantity' => $productData['quantity']
                    ]);
                });

                $order->orderProducts()->saveMany($products);
            }

            return $order->load('orderProducts');
        });
    }
}
