<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use App\Models\Order;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryPartnerController extends Controller
{
    public function index()
    {
        $deliveryPartners = DeliveryPartner::latest()
            ->paginate(10);

        return view('admin.delivery-partners.index', compact('deliveryPartners'));
    }
    public function show($id)
    {
        $deliveryPartner = DeliveryPartner::withTrashed()->findOrFail($id);

        return view('admin.delivery-partners.show', compact('deliveryPartner'));
    }

    public function approve($id)
    {
        $partner = DeliveryPartner::findOrFail($id);

        $partner->update([
            'is_verified' => 1,
            'verification_status' => 'approved',
            'status' => 'active',
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Delivery partner approved successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $partner = DeliveryPartner::findOrFail($id);

        $partner->update([
            'is_verified' => 0,
            'verification_status' => 'rejected',
            'status' => 'inactive',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Delivery partner rejected successfully.');
    }

    public function updateVerification(Request $request, $id)
    {
        $request->validate([
            'verification_status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $partner = DeliveryPartner::findOrFail($id);

        $status = $request->verification_status;

        $partner->verification_status = $status;
        $partner->is_verified = $status === 'approved';

        if ($status === 'approved') {
            $partner->status = 'active';
            $partner->rejection_reason = null;
        } elseif ($status === 'rejected') {
            $partner->status = 'inactive';
            $partner->rejection_reason = $request->rejection_reason;
        } else {
            $partner->status = 'inactive';
            $partner->rejection_reason = null;
        }

        $partner->save();

        return back()->with('success', 'Delivery partner verification updated successfully.');
    }

    public function settlements($id)
    {
        $partner = DeliveryPartner::withTrashed()->findOrFail($id);

        // Delivered Orders Query
        $query = Order::where('delivery_partner_id', $partner->id)
            ->where('status', 'delivered');

        // Total Earnings
        $totalEarnings = (float) (clone $query)
            ->sum('delivery_earning');

        // Total Delivered Orders
        $totalDeliveredOrders = (clone $query)->count();

        // Settled Earnings
        $settledEarnings = (float) ($partner->settled_earnings ?? 0);

        // Pending Earnings
        $pendingEarnings = max(0, $totalEarnings - $settledEarnings);

        // Settled Earnings History
        $settlements = Settlement::where(
            'delivery_partner_id',
            $partner->id
        )
            ->latest('settled_at')
            ->paginate(15, ['*'], 'settlements_page');

        // Delivered Orders History
        $orders = (clone $query)
            ->latest('delivered_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.delivery-partners.settlements', compact(
            'partner',
            'orders',
            'totalEarnings',
            'totalDeliveredOrders',
            'settledEarnings',
            'pendingEarnings',
            'settlements'
        ));
    }



    public function storeSettlement(Request $request, $id)
    {
        $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'decimal:0,2',
            ],
            'reference_number' => [
                'nullable',
                'string',
                'max:255',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        try {

            DB::transaction(function () use ($request, $id) {

                $partner = DeliveryPartner::whereKey($id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $availableAmount = round((float) $partner->totalEarnings, 2);

                $settlementAmount = round((float) $request->amount, 2);

                // Create settlement record
                Settlement::create([
                    'delivery_partner_id' => $partner->id,
                    'amount' => $settlementAmount,
                    'settlement_status' => 'completed',
                    'settled_by' => Auth::guard('admin')->id(),
                    'reference_number' => 'SET-' . strtoupper(uniqid()),
                    'notes' => $request->notes,
                    'settled_at' => now(),
                ]);

                // Update partner earnings
                $partner->pending_earnings = round(
                    $availableAmount - $settlementAmount,
                    2
                );

                $partner->settled_earnings = round(
                    (float) ($partner->settled_earnings ?? 0) + $settlementAmount,
                    2
                );

                $partner->save();
            });

            return redirect()
                ->back()
                ->with('success', 'Settlement completed successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {

            \Log::error('Delivery Partner Settlement Error', [
                'partner_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->with('error', 'Unable to process settlement. Please try again.');
        }
    }


}
