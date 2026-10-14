<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Coupon::query();
        $data = $data->latest()->paginate(20);
        return view('admin.coupons.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'discount' => 'required|numeric',
            'minimum_purchase' => 'nullable|numeric',
            // 'limit' => 'nullable|integer',
            'expiry_date' => 'nullable|date',
        ]);

        Coupon::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'discount' => $request->discount,
            'minimum_purchase' => $request->minimum_purchase,
            // 'limit' => $request->limit,
            'expiry_date' => $request->expiry_date,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon added successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->back()->with('success', 'Coupon deleted successfully!');
    }
}
