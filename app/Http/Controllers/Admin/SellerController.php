<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Carbon\Carbon;
use Validator;
Use Alert;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = Seller::query();
        if (!empty($request->search)) {
            $data = $data->where(function ($query) use ($request) {

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
        $sellers = $data->latest()->paginate(10);
        return view('admin.sellers.all', compact('sellers'));
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
        $user = Auth::guard('admin')->user();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email|max:255', // Unique check
            'mobile' => 'required|numeric|unique:sellers,mobile', // Unique check
         //   'password' => 'required|min:4|confirmed',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);
       // Check if validation fails
        if ($validator->fails()) {
            $errorMessages = implode('<br>', $validator->errors()->all());
            Alert::html('Validation Error', $errorMessages, 'error');
            return redirect()->back();
        }
        $seller = new Seller();
        $seller->name = $request->name;
        $seller->mobile = $request->mobile;
        $seller->email = $request->email;
        $seller->address = $request->address;
        $seller->kyc_status = $request->kyc_status;
        $seller->created_at = Carbon::now();
        $seller->save();

        Alert::toast("Seller added Successfully", 'success');
        return redirect(route('sellers.index'));
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
    }
}
