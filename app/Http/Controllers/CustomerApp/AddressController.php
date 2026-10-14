<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Address;
use App\Http\Resources\AddressCollection;
use Illuminate\Support\Facades\Validator;
use App\Models\State;
class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $user = auth('sanctum')->user();
        if ($user) {
            $my_locations = Address::where('customer_id', $user->id)->paginate(10);

            if (count($my_locations) > 0) {
                $data = new AddressCollection($my_locations);
                $responseData = array('success' => 1, 'data' => $data, 'message' => "Locations Fetched Successfully.");
            } else {
                $responseData = array('success' => 0, 'message' => "No Added Locations Found.");

            }

            $userResponse = json_encode($responseData);

            return $userResponse;

        } else {

            $responseData = array('success' => 0, 'message' => "Authentication Failed");

            $userResponse = json_encode($responseData);

            return $userResponse;

        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            $responseData = array('success' => 0, 'message' => 'Please Login');
            return json_encode($responseData);
        }
        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'alternate_mobile' => 'nullable|string|max:15',
            'address' => 'required|string',
            'pincode' => 'required|string|max:10',
            //'default' => 'required|boolean',
            // 'state_id' => 'nullable|exists:states,id',
            // 'state' => 'required',
            'email' => 'nullable|email',
            'location_id' => 'nullable|exists:addresses,id,customer_id,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => $validator->errors()->first()
            ]);
        }
        $checkLocation = Address::where(['customer_id' => $user->id])->get();
        if (count($checkLocation) > 0) {
            if ($request->default == true) {
                $update = Address::where(['customer_id' => $user->id])->update(['default' => true]);
            }
        }
        if (!empty($request->location_id)) {
            $location = Address::where('id', $request->location_id)->first();
            if (!isset($location->id)) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Location Not Found'
                ]);
            }
        } else {
            $location = new Address();
            $location->customer_id = $user->id;
        }
        $location->name = $request->name;
        $location->mobile = $request->mobile;
        $location->alternate_mobile = $request->alternate_mobile;
        $location->address = $request->address;
        $location->landmark = $request->landmark;
        $location->email = $request->email;
        $location->gst = $request->gst;
        $location->address_2 = $request->address_2;
        // $location->country = $request->country;
        // if (!empty($request->city_id)) {
        //     $city = City::find($request->city_id);
        $location->city = $request->city ?? null;
        // }
        // if (!empty($request->state_id)) {
        //     $state = State::find($request->state_id);
        //     $location->state = $state->name ?? null;
        // }
        $location->state = $request->state ?? null;
        $location->pincode = $request->pincode;
        $location->default = in_array(strtolower($request->input('default')), ['yes', '1', 'true', 'on']);
        // $location->city_id = $request->city_id;
        $location->state_id = $request->state_id;
        // $location->state = $request->state;
        if ($location->save()) {
            $data = new AddressCollection(Address::where('id', $location->id)->get());

            $response = array('success' => 1, 'data' => $data, 'message' => 'Location Updated Successfully');
        } else {
            $response = array('success' => 0, 'message' => 'Couldnt Update Location');

        }
        return json_encode($response);
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
        $my_location = Address::find($id);

            if ($my_location) {
                $my_location->delete();
                $responseData = array('success' => 1,  'message' => "Locations Deleted Successfully.");
            } else {
                $responseData = array('success' => 0, 'message' => "No Location Found.");

            }

            $userResponse = json_encode($responseData);

            return $userResponse;
    }
        public function states(Request $request)
    {

        $states = State::get();
        if (count($states) > 0) {
                return response()->json([
                    'success' => 1,
                    'data' => $states,
                    'message' => "Fetched Successfully."
                ]);
        } else {
                return response()->json([
                    'success' => 0,
                    'message' => 'No Data Found'
                ]);
        }

    }
}
