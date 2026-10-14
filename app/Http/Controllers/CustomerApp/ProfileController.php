<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryCollection;
use App\Http\Resources\ProfileCollection;
use App\Models\City;
use App\Models\Location;
use App\Models\State;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
use App\Http\Resources\BannerCollection;

class ProfileController extends Controller
{
    public function banners(Request $request)
    {
        $user = auth('sanctum')->user();
        $banners = Banner::where(['status' => 'show'])->latest()->get();
        $data = new BannerCollection($banners);

        if (count($data) > 0) {
            return response()->json([
                'success' => 1,
                'data' => $data,
                'message' => ' Banners Fetched Successfully'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => "No Data Found"
            ]);
        }
    }
    public function profile()
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            return response()->json([
                'success' => 9,
                'message' => 'Please Login'
            ]);
        }

        $data = new ProfileCollection(Customer::where('id', $user->id)->get());
        return response()->json([
            'success' => 1,
            'data' => $data,
            'message' => 'User Fetch Successfully'
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            return response()->json([
                'success' => 9,
                'message' => 'Please Login'
            ]);
        }
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:customers,email,' . $user->id,
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'errors' => $validator->errors(),
                'message' => 'Validation failed.'
            ]);
        }
        $user->name = $request->name;
        // $user->city_id = $request->city_id;
        // $user->state_id = $request->state_id;
        // $user->address = $request->address;
        if (!empty($request->email)) {
            $user->email = $request->email;
        }
        if (!empty($request->hasFile('profile_pic'))) {
            $user->profile_pic = $request->profile_pic->store('profile');
        }
        if ($user->save()) {
            $data = new ProfileCollection(Customer::where('id', $user->id)->get());
            return response()->json([
                'success' => 1,
                'data' => $data,
                'message' => 'User Updated Successfully'
            ]);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'User Data Cant Update Right Now'
            ]);
        }

    }




    public function deliveryLocation(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            $responseData = array('success' => 0, 'message' => 'Please Login');
            return json_encode($responseData);
        }
        $validator = Validator::make($request->all(), [
            'default' => 'required',
            'city_id' => 'required',
        ]);
        if ($validator->fails()) {
            $responseData = array('success' => 0, 'message' => $validator->errors()->first());
            return json_encode($responseData);
        }
        $checkLocation = Location::where(['user_id' => $user->id])->get();
        if (count($checkLocation) > 0) {
            if ($request->default == 'Yes') {
                $update = Location::where(['user_id' => $user->id])->update(['default' => 'No']);
            }
        }
        if (!empty($request->location_id)) {
            $location = Location::where('id', $request->location_id)->first();
            if (!isset($location->id)) {
                return response()->json([
                    'success' => 0,
                    'message' => 'Location Not Found'
                ]);
            }
        } else {
            $location = new Location();
            $location->user_id = $user->id;
        }
        $location->name = $request->name;
        $location->mobile = $request->mobile;
        $location->door_no = $request->door_no;
        $location->landmark = $request->landmark;
        $location->email = $request->email ?? '';
        $location->address = $request->address;
        $location->country = $request->country;
        if (!empty($request->city_id)) {
            $city = City::find($request->city_id);
            $location->city = $city->name ?? null;
        }
        if (!empty($request->state_id)) {
            $state = State::find($request->state_id);
            $location->state = $state->name ?? null;
        }
        $location->pincode = $request->pincode;
        $location->default = $request->default;
        $location->city_id = $request->city_id;
        $location->state_id = $request->state_id;
        if ($location->save()) {
            $data = new DeliveryCollection(Location::where('id', $location->id)->get());

            $response = array('success' => 1, 'data' => $data, 'message' => 'Location Updated Successfully');
        } else {
            $response = array('success' => 0, 'message' => 'Couldnt Update Location');

        }
        return json_encode($response);
    }



    public function my_locations(Request $request)
    {

        $user = auth('sanctum')->user();
        if ($user) {
            $my_locations = Location::where('user_id', $user->id)->paginate(10);

            if (count($my_locations) > 0) {
                $data = new DeliveryCollection($my_locations);
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
    public function deleteLocation(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            $responseData = array('success' => 0, 'message' => 'Please Login');
            return json_encode($responseData);
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $responseData = array('success' => 0, 'message' => $validator->errors()->first());
            return json_encode($responseData);
        }
        $data = Location::where(['id' => $request->id, 'user_id' => $user->id])->delete();
        if ($data) {
            $response = array('success' => 1, 'message' => 'Location Deletion Successfully');
        } else {
            $response = array('success' => 0, 'message' => 'Deletion Unsuccesssfull');
        }
        return json_encode($response);
    }
    public function firebase_token(Request $request)
    {
        $user = auth('sanctum')->user();
        if (!isset($user->id)) {
            $responseData = array('success' => 0, 'message' => 'Please Login');
            return json_encode($responseData);
        }
        $token = Customer::where('id', $user->id)->update(['firebase_token' => $request->firebase_token]);

        return response()->json([

            'success' => 1,
            'message' => 'Updated Successfully...',


        ]);

    }
}
