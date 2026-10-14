<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Address;
use Carbon\Carbon;
use Validator;
Use Alert;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $data = Customer::query();
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
        $customers = $data->latest()->paginate(10);
        return view('admin.customers.all', compact('customers'));
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
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->slug = $this->slugGenerate($request->name, 0);
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {
            $product_image = request()->file('image');
            $productName = $customer->slug . "." . 'webp';
            $image = $manager->read($product_image);
            $image->resize(866, 541);
            $image->toWebp(70)->save(public_path('admin/build/img/customer/') . $productName);
            $imagePath = 'admin/build/img/customer/' . $productName;
            $customer->profile_pic = $imagePath;
        }
        $customer->email = $request->email ?? 0;
        $customer->mobile = $request->mobile;
        $customer->save();

        $address = new Address();
        $address->customer_id = $customer->id;
        $address->address = $request->address;
        $address->pincode = $request->pincode;
        $address->landmark = $request->landmark;
        $address->state = $request->state;
        $address->save();

        $customer->address_id = $address->id;
        $customer->save();

        Alert::toast('Customer created Succesfully', 'success');
        return redirect(route('customer.index'));
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
        $checkSlug = Customer::where('slug', $slug)->exists();
        if (!$checkSlug) {
            return $slug;
        } else {
            // Increment count and retry
            return $this->slugGenerate($name, $count + 1);
        }
    }
}
