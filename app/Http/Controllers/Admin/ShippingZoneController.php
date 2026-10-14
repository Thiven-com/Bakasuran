<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreightCharge;
use App\Models\ShippingZone;
use App\Models\State;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ShippingZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = ShippingZone::get();
        return view('admin.shipping_zone.all', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $states = State::get();
        return view('admin.shipping_zone.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->all());
        $data = new ShippingZone();
        $data->zone_name = $request->zone_name;
        $data->slug = $this->slugGenerate($request->zone_name, 0);
        $data->regions = json_encode($request->regions);
        $data->shipping_method = $request->shipping_method;
        $data->free_shipping = $request->free_shipping;
        $data->save();
        Alert::toast('Shipping Zone Created Successfully', 'success');
        return redirect(route('admin.shippingZones.index'));
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
    public function edit($id)
    {
        $zone = ShippingZone::with('charges')->findOrFail($id);
        $states = State::all();

        return view('admin.shipping_zone.edit', compact('zone', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'zone_name' => 'required',
            'shipping_method' => 'required',
            'regions' => 'required|array',
            'free_shipping' => 'required',
            'min_weight.*' => 'required|numeric',
            'max_weight.*' => 'required|numeric',
            'charge.*' => 'required|numeric',
        ]);

        $zone = ShippingZone::findOrFail($id);

        $zone->update([
            'zone_name' => $request->zone_name,
            'shipping_method' => $request->shipping_method,
            'regions' => json_encode($request->regions),
            'free_shipping' => $request->free_shipping,
        ]);

        // Remove old charges
        // $zone->charges()->delete();

        // // Insert new charges
        // if ($request->min_weight) {
        //     foreach ($request->min_weight as $key => $minWeight) {
        //         $zone->charges()->create([
        //             'min_weight' => $minWeight,
        //             'max_weight' => $request->max_weight[$key],
        //             'charge' => $request->charge[$key],
        //         ]);
        //     }
        // }


        $existingIds = $zone->charges()->pluck('id')->toArray();
        $submittedIds = [];

        if ($request->min_weight) {

            foreach ($request->min_weight as $key => $minWeight) {

                $id = $request->charge_id[$key] ?? null;

                // ✅ UPDATE EXISTING
                if (!empty($id)) {

                    $charge = $zone->charges()->where('id', $id)->first();

                    if ($charge) {
                        $charge->update([
                            'min_weight' => $minWeight,
                            'max_weight' => $request->max_weight[$key],
                            'charge' => $request->charge[$key],
                        ]);

                        $submittedIds[] = $id;
                    }
                } else {
                    // ✅ CREATE NEW
                    $new = $zone->charges()->create([
                        'min_weight' => $minWeight,
                        'max_weight' => $request->max_weight[$key],
                        'charge' => $request->charge[$key],
                    ]);

                    $submittedIds[] = $new->id;
                }
            }
        }

        # ✅ DELETE REMOVED ROWS
        $toDelete = array_diff($existingIds, $submittedIds);

        if (!empty($toDelete)) {
            $zone->charges()->whereIn('id', $toDelete)->delete();
        }

        return redirect()->route('admin.shippingZones.index')
            ->with('success', 'Zone Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $zone = ShippingZone::find($id);
        if (!isset($zone->id)) {
            return response()->json([
                'success' => 0,
                'message' => "No Data Found"
            ]);
        }
        FreightCharge::where('shipping_zone_id', $zone->id)->delete();
        $zone->delete();
        return response()->json([
            'success' => 0,
            'message' => "Deleted Successfully"
        ]);
    }

    private function slugGenerate($name, $count)
    {

        if (preg_match("/[\/\\\^£$%&*()}{@#~?><>,|=_+¬-]/", $name)) {
            $name = str_replace(']', '_', $name);
            $name = str_replace('[', '_', $name);
            $name = str_replace("'", '_', $name);
            $pattern = "/[\/\\\^£$%&*()}{@#~?><>,|=_+¬-]/";
            $replacement = "_";

            // Replace special characters with underscores
            $name = preg_replace($pattern, $replacement, $name);
        }
        $slug = ($count == 0) ? strtolower(str_replace(' ', '_', $name)) : strtolower(str_replace(' ', '_', $name)) . $count;

        // Check for uniqueness
        $checkSlug = ShippingZone::where('slug', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }
}
