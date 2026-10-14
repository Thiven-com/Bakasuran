<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSale;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::orderByDesc('starts_at')->paginate(20);
        return view('admin.flash_sales.index', compact('flashSales'));
    }

    public function create()
    {
        // We will use modal forms in index, so you may not need this.
        return redirect()->route('flash-sales.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'starts_at' => 'nullable|date',
            'ends_at' => 'required|date|after:starts_at',
            'status' => 'nullable|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $fs = new FlashSale();
            $fs->title = $request->title;
            $fs->description = $request->description;
            $fs->starts_at = $request->starts_at ? Carbon::parse($request->starts_at) : null;
            $fs->ends_at = Carbon::parse($request->ends_at);
            $fs->status = $request->has('status') ? 1 : 0;

            if ($request->hasFile('banner')) {
                $file = $request->file('banner');
                $fname = 'flash_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('media/flash_sales', $fname, 'public');
                // store as public path for asset()
                $fs->banner = 'storage/' . $path;
            }

            $fs->save();

            DB::commit();
            return redirect()->route('flash-sales.index')->with('success', 'Flash sale created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('FlashSale store error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Unable to create flash sale');
        }
    }

    public function show(FlashSale $flashSale)
    {
        return response()->json([
            'success' => 1,
            'data' => $flashSale
        ]);
    }

    public function edit(FlashSale $flashSale)
    {
        // we will use AJAX show() to populate modal; redirect to index for non-AJAX
        return redirect()->route('flash-sales.index');
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'starts_at' => 'nullable|date',
            'ends_at' => 'required|date|after:starts_at',
            'status' => 'nullable|in:0,1',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => 0, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $flashSale->title = $request->title;
            $flashSale->description = $request->description;
            $flashSale->starts_at = $request->starts_at ? Carbon::parse($request->starts_at) : null;
            $flashSale->ends_at = Carbon::parse($request->ends_at);
            $flashSale->status = $request->has('status') ? 1 : 0;

            if ($request->hasFile('banner')) {
                // delete old
                if (!empty($flashSale->banner)) {
                    // banner saved as storage/media/..., so remove from storage disk public
                    $old = str_replace('storage/', '', $flashSale->banner);
                    if (\Storage::disk('public')->exists($old)) {
                        \Storage::disk('public')->delete($old);
                    }
                }
                $file = $request->file('banner');
                $fname = 'flash_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('media/flash_sales', $fname, 'public');
                $flashSale->banner = 'storage/' . $path;
            }

            $flashSale->save();
            DB::commit();

            if ($request->wantsJson()) {
                return response()->json(['success' => 1, 'message' => 'Updated']);
            }
            return redirect()->route('flash-sales.index')->with('success', 'Flash sale updated');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('FlashSale update error: '.$e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => 0, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Unable to update flash sale');
        }
    }

    public function destroy(Request $request, $id)
    {
        $flashSale = FlashSale::find($id);
        if (!$flashSale) {
            if ($request->wantsJson()) return response()->json(['success' => 0, 'message' => 'Not found'], 404);
            return redirect()->route('flash-sales.index')->with('error', 'Not found');
        }

        DB::beginTransaction();
        try {
            // delete banner file
            if (!empty($flashSale->banner)) {
                $old = str_replace('storage/', '', $flashSale->banner);
                if (Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
            }

            $flashSale->delete();
            DB::commit();

            if ($request->wantsJson()) return response()->json(['success' => 1, 'message' => 'Deleted']);
            return redirect()->route('flash-sales.index')->with('success', 'Deleted');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('FlashSale destroy error: '.$e->getMessage());
            if ($request->wantsJson()) return response()->json(['success' => 0, 'message' => $e->getMessage()], 500);
            return redirect()->back()->with('error', 'Unable to delete');
        }
    }
}
