<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $data = Admin::where('role', 'staff');
        $data = $data->paginate(20);
        return view('admin.staff.index', compact('data'));
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
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:admins,email',
            'mobile'            => 'required|unique:admins,mobile',
            'password'          => 'required|min:6|confirmed',
        ]);
        if (Admin::where('email', $request->email)->exists()) {
            return redirect()->back()
                ->withErrors(['email' => 'This email is already taken.'])
                ->withInput();
        }
        $staff = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'mobile'   => $request->mobile,
            'password' => bcrypt($request->password),
            'role'     => 'staff',
        ]);
        return redirect()->back()->with('success', 'Staff added successfully!');
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
        $item = Admin::findOrFail($id);

        $item->status = $request->has('status') ? 'active' : 'inactive';

        $item->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $staff = Admin::findOrFail($id);
        $staff->delete();

        return redirect()->back()->with('success', 'Staff deleted successfully.');
    }
}
