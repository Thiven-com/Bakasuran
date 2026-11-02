<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddonGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class AddonGroupController extends Controller
{
    public function index()
    {
        $groups = AddonGroup::withCount('options')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('admin.catalog.addons.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.catalog.addons.groups.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'selection_type' => 'required|in:single,multiple',
            'is_required' => 'nullable|boolean',
            'min_select' => 'required|integer|min:0',
            'max_select' => 'nullable|integer|min:1',
            'status' => 'required|in:show,hide',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (
            $validated['max_select'] !== null &&
            $validated['max_select'] < $validated['min_select']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'max_select' => 'Maximum selections cannot be less than minimum selections.'
                ]);
        }

        $validated['slug'] = Str::slug($validated['name'])
            . '-' . Str::lower(Str::random(6));

        $validated['is_required'] = $request->boolean('is_required');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        AddonGroup::create($validated);

        Alert::toast('Add-on group created successfully', 'success');

        return redirect()->route('admin.addon-groups.index');
    }

    public function edit(string $id)
    {
        $group = AddonGroup::findOrFail($id);

        return view('admin.catalog.addons.groups.edit', compact('group'));
    }

    public function update(Request $request, string $id)
    {
        $group = AddonGroup::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'selection_type' => 'required|in:single,multiple',
            'is_required' => 'nullable|boolean',
            'min_select' => 'required|integer|min:0',
            'max_select' => 'nullable|integer|min:1',
            'status' => 'required|in:show,hide',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (
            $validated['max_select'] !== null &&
            $validated['max_select'] < $validated['min_select']
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'max_select' => 'Maximum selections cannot be less than minimum selections.'
                ]);
        }

        $validated['is_required'] = $request->boolean('is_required');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $group->update($validated);

        Alert::toast('Add-on group updated successfully', 'success');

        return redirect()->route('admin.addon-groups.index');
    }

    public function destroy(string $id)
    {
        $group = AddonGroup::findOrFail($id);

        if ($group->products()->exists()) {
            Alert::toast(
                'Remove this group from assigned products before deleting it.',
                'warning'
            );

            return redirect()->back();
        }

        $group->delete();

        Alert::toast('Add-on group deleted successfully', 'success');

        return redirect()->route('admin.addon-groups.index');
    }
}
