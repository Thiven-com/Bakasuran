<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantFoodOrder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RestaurantFoodOrderController extends Controller
{
    // Display all dine-in food orders
    public function index()
    {
        $orders = RestaurantFoodOrder::with([
            'restaurant',
            'table',
            'booking',
            'booking.foodOrders',
            'items'
        ])->latest()->paginate(15);

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
        'booking',
        'items',
        'booking.foodOrders.items',
    ])->findOrFail($id);

    return view(
        'admin.catalog.restaurant-food-orders.show',
        compact('order')
    );
}


    // Generate Dine-In Bill
   
public function generateBill($id)
{
    $order = RestaurantFoodOrder::with('booking.foodOrders')
        ->findOrFail($id);

    $orders = $order->booking
        ? $order->booking->foodOrders
        : collect([$order]);

    // All orders must be served before generating the bill
    if ($orders->contains(fn($item) => $item->status !== 'served')) {
        return back()->with(
            'error',
            'All orders must be served before generating the final bill.'
        );
    }

    // Prevent duplicate bill generation
    if ($orders->contains(fn($item) => $item->bill_generated_at)) {
        return back()->with(
            'error',
            'Bill has already been generated for this booking.'
        );
    }

    $billGeneratedAt = now();

    foreach ($orders as $item) {
        $item->update([
            'bill_generated_at' => $billGeneratedAt,
            'payment_status' => 'pending',
        ]);
    }

    return back()->with(
        'success',
        'Combined dine-in bill generated successfully.'
    );
}




    // Confirm Cash or UPI Payment
    

public function confirmOfflinePayment(Request $request, $id)
{
    $validated = $request->validate([
        'payment_method' => 'required|in:cash,upi',
    ]);

    $order = RestaurantFoodOrder::with('booking.foodOrders')
        ->findOrFail($id);

    $orders = $order->booking
        ? $order->booking->foodOrders
        : collect([$order]);

    // Check whether all bills are generated
    if ($orders->contains(fn($item) => !$item->bill_generated_at)) {
        return back()->with(
            'error',
            'Combined bill has not been generated for all orders.'
        );
    }

    // Prevent duplicate payment confirmation
    if ($orders->every(fn($item) => $item->payment_status === 'paid')) {
        return back()->with(
            'error',
            'Payment is already confirmed.'
        );
    }

    // Do not allow offline confirmation for Razorpay orders
    if ($orders->contains(fn($item) => $item->payment_method === 'razorpay')) {
        return back()->with(
            'error',
            'Razorpay payments must be verified online.'
        );
    }

    // Update every order under this booking
    foreach ($orders as $item) {
        $item->update([
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    return back()->with(
        'success',
        'Combined payment confirmed successfully for all orders.'
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

        // Payment must be confirmed before completing the order
        if (
            $request->status === 'completed' &&
            $order->payment_status !== 'paid'
        ) {
            return back()->with(
                'error',
                'Payment must be confirmed before completing the order.'
            );
        }

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with(
            'success',
            'Dine-in order status updated successfully.'
        );
    }



public function completeAllOrders($id)
{
    return DB::transaction(function () use ($id) {

        $order = RestaurantFoodOrder::findOrFail($id);

        $orders = $order->restaurant_table_booking_id
            ? RestaurantFoodOrder::where(
                'restaurant_table_booking_id',
                $order->restaurant_table_booking_id
            )->lockForUpdate()->get()
            : RestaurantFoodOrder::whereKey($order->id)
                ->lockForUpdate()->get();

        if ($orders->contains(
            fn($item) => !in_array($item->status, ['served', 'completed'])
        )) {
            return back()->with(
                'error',
                'All orders must be served before completion.'
            );
        }

        if ($orders->contains(
            fn($item) => $item->payment_status !== 'paid'
        )) {
            return back()->with(
                'error',
                'Please confirm the combined payment first.'
            );
        }

        foreach ($orders as $item) {
            $item->update([
                'status' => 'completed',
            ]);
        }

        return back()->with(
            'success',
            'All orders in this table booking completed successfully.'
        );
    });
}



    public function destroy($id)
    {
        $order = RestaurantFoodOrder::findOrFail($id);

        \DB::transaction(function () use ($order) {

            // Delete related food items first
            $order->items()->delete();

            // Delete the main order
            $order->delete();
        });

        return redirect()
            ->route('admin.restaurant-food-orders.index')
            ->with('success', 'Restaurant food order deleted successfully.');
    }
}
