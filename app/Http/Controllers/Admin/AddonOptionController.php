<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonGroup;
use App\Models\AddonOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AddonOptionController extends Controller
{
    // Display all add-on options
    public function index()
    {
        $options = AddonOption::with('group')
            ->withCount('group')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view(
            'admin.catalog.addons.options.index',
            compact('options')
        );
    }

    // Show create form
    public function create()
    {
        $groups = AddonGroup::where('status', 'show')
            ->orderBy('sort_order')
            ->get();

        return view(
            'admin.catalog.addons.options.create',
            compact('groups')
        );
    }

    // Store new option
    public function store(Request $request)
    {
        $validated = $request->validate([
            'addon_group_id' => 'required|exists:addon_groups,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',

            'track_stock' => 'nullable|boolean',
            'stock' => 'required_if:track_stock,1|integer|min:0',

            'low_stock_alert' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['track_stock'] = $request->boolean('track_stock');
        $validated['is_available'] = $request->boolean('is_available');

        // Save stock only when stock tracking is enabled
        if ($validated['track_stock']) {
            $validated['stock'] = (int) $validated['stock'];
        } else {
            $validated['stock'] = null;
            $validated['low_stock_alert'] = null;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        AddonOption::create($validated);

        Alert::toast(
            'Add-on option created successfully',
            'success'
        );

        return redirect()->route('admin.addon-options.index');
    }

    // Show edit form
    public function edit(string $id)
    {
        $option = AddonOption::findOrFail($id);

        $groups = AddonGroup::orderBy('sort_order')->get();

        return view(
            'admin.catalog.addons.options.edit',
            compact('option', 'groups')
        );
    }

    // Update option
    public function update(Request $request, string $id)
    {
        $option = AddonOption::findOrFail($id);

        $validated = $request->validate([
            'addon_group_id' => 'required|exists:addon_groups,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',

            'track_stock' => 'nullable|boolean',
            'stock' => 'required_if:track_stock,1|integer|min:0',

            'low_stock_alert' => 'nullable|integer|min:0',
            'is_available' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['track_stock'] = $request->boolean('track_stock');
        $validated['is_available'] = $request->boolean('is_available');

        // Save stock only when stock tracking is enabled
        if ($validated['track_stock']) {
            $validated['stock'] = (int) $validated['stock'];
        } else {
            $validated['stock'] = null;
            $validated['low_stock_alert'] = null;
        }

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $option->update($validated);

        Alert::toast(
            'Add-on option updated successfully',
            'success'
        );

        return redirect()->route('admin.addon-options.index');
    }

    // Delete option
    public function destroy(string $id)
    {
        $option = AddonOption::findOrFail($id);

        // Check whether option is used in cart
        $usedInCart = DB::table('cart_item_addons')
            ->where('addon_option_id', $option->id)
            ->exists();

        // Check whether option is used in orders
        $usedInOrders = DB::table('order_item_addons')
            ->where('addon_option_id', $option->id)
            ->exists();

        if ($usedInCart || $usedInOrders) {
            Alert::toast(
                'This option is already used in cart or order records. Mark it unavailable instead.',
                'warning'
            );

            return redirect()->back();
        }

        $option->delete();

        Alert::toast(
            'Add-on option deleted successfully',
            'success'
        );

        return redirect()->route('admin.addon-options.index');
    }
}
