<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantFoodOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantFoodOrderController extends Controller
{
    // Display all dine-in food orders
    public function index()
    {
        $orders = RestaurantFoodOrder::with([
            'restaurant',
            'table',
            'items',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.catalog.restaurant-food-orders.index',
            compact('orders')
        );
    }

    // Display individual order
    public function show($id)
    {
        $order = RestaurantFoodOrder::with([
            'restaurant',
            'table',
            'items',
        ])->findOrFail($id);

        return view(
            'admin.catalog.restaurant-food-orders.show',
            compact('order')
        );
    }

    // Update kitchen order status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'accepted',
                    'preparing',
                    'ready_to_serve',
                    'served',
                    'completed',
                    'cancelled',
                ]),
            ],
        ]);

        $order = RestaurantFoodOrder::findOrFail($id);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Dine-in order status updated successfully.'
        );
    }
}
