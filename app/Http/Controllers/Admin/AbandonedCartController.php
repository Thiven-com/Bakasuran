<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AbandonedCartMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

class AbandonedCartController extends Controller
{
    //
    public function abandonedCart(Request $request)
    {
        $customers = Customer::query();
        if (!empty($request->search)) {
            $customers = $customers->where(function ($query) use ($request) {

                return $query
                    ->where(function ($query) use ($request) {
                        return $query
                            ->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere(function ($query) use ($request) {
                        return $query
                            ->where('email', 'like', '%' . $request->search . '%');
                    })
                    ->orWhere(function ($query) use ($request) {
                        return $query
                            ->where('mobile', 'like', '%' . $request->search . '%');
                    });
            });
        }
        $customers = $customers->whereHas('carts')->withSum('carts as cart_total', DB::raw('quantity * unit_price'))->withCount('carts as cart_items_count');
        $customers = $customers->get();
        return view('admin.abandoned_cart.customers', compact('customers'));
    }
    public function viewAbandonedCart($id)
    {
        $customer = Customer::with('carts')->withSum('carts as cart_total', DB::raw('quantity * unit_price'))->find($id);
        if (!isset($customer->id)) {
            Alert::toast("Customer Details Not Found", 'warning');
            return redirect(route('admin.abandonedCart'));
        }
        return view('admin.abandoned_cart.view_abandoned_cart', compact('customer'));
    }

    public function sendMail($id)
    {
        $customer = Customer::withSum('carts as cart_total', DB::raw('quantity * unit_price'))->find($id); // Your customer model
        if (!isset($customer->id)) {
            Alert::toast("Customer Details Not Found", 'warning');
            return redirect(route('admin.abandonedCart'));
        }
        $cartItems = $customer->carts; // Make sure you have a relationship set up
        if (empty($customer->email)) {
            Alert::toast("Email Not Found", 'warning');
            return redirect(route('admin.abandonedCart'));
        }
        try {
            Mail::to($customer->email)->send(new AbandonedCartMail($customer, $cartItems));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }

        return redirect()->back()->with('success', 'Mail sent successfully to ' . $customer->name);
    }

    public function sendSelectedMail(Request $request)
    {
        $ids = $request->customer_ids;

        if (!$ids || count($ids) == 0) {
            return back()->with('error', 'Please select at least one customer');
        }

        $customers = Customer::with('carts')
            ->withSum('carts as cart_total', DB::raw('quantity * unit_price'))
            ->whereIn('id', $ids)
            ->get();
        $sent = 0;

        foreach ($customers as $customer) {

            if (empty($customer->email)) continue;

            try {
                Mail::to($customer->email)
                    ->send(new AbandonedCartMail($customer, $customer->carts));

                $sent++;
            } catch (\Exception $e) {
                Log::error('Selected bulk mail error', [
                    'customer_id' => $customer->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return back()->with('success', "Mail sent successfully to {$sent} customers");
    }
}
