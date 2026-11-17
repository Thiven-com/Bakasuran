<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::latest()->paginate(10);

        return view('admin.setting.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'key' => 'nullable|string|max:255',
            'value' => 'nullable|string',
            'dataType' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:show,hide',
            'category_id' => 'nullable|integer',
        ]);

        Setting::create($request->only([
            'name',
            'key',
            'value',
            'dataType',
            'description',
            'status',
            'category_id',
        ]));

        return redirect()->route('admin.setting.index')
            ->with('success', 'Setting created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'key' => 'nullable|string|max:255',
            'value' => 'nullable|string',
            'dataType' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:show,hide',
            'category_id' => 'nullable|integer',
        ]);

        $setting = Setting::findOrFail($id);

        $setting->update($request->only([
            'name',
            'key',
            'value',
            'dataType',
            'description',
            'status',
            'category_id',
        ]));

        return redirect()->route('admin.setting.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);

        $setting->delete();

        return redirect()->route('admin.setting.index')
            ->with('success', 'Setting deleted successfully.');
    }
}
