<?php

namespace App\Http\Controllers\RestaurantPanel;

use App\Http\Controllers\Controller;
use App\Models\RestaurantFoodOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $restaurant = Auth::guard('restaurant')->user();

        $orders = RestaurantFoodOrder::where(
            'restaurant_id',
            $restaurant->id
        );

        $totalOrders = (clone $orders)->count();

        $pendingOrders = (clone $orders)
            ->where('status', 'pending')
            ->count();

        $completedOrders = (clone $orders)
            ->where('status', 'completed')
            ->count();

        $totalRevenue = (clone $orders)
            ->where('payment_status', 'paid')
            ->sum('grand_total');

        return view('restaurantpanel.auth.dashboard', compact(
            'restaurant',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue'
        ));
    }
}