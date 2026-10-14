<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ActivityLogController extends Controller
{
    //
    public function index()
    {
        $user = Auth::guard('admin')->user();
        if (!isset($user->id)) {
            Alert::toast('Please Login', 'warning');
            return redirect(route('admin.login'));
        }
        if ($user->role == 'staff') {
            Alert::toast('Dont Have Access', 'warning');
            return redirect(route('admin.dashboard'));
        }
        $logs = ActivityLog::latest()
            ->paginate(20);

        return view('admin.activity_logs.index', compact('logs'));
    }

    public function show($id)
    {
        $user = Auth::guard('admin')->user();
        if (!isset($user->id)) {
            Alert::toast('Please Login', 'warning');
            return redirect(route('admin.login'));
        }
        if ($user->role == 'staff') {
            Alert::toast('Dont Have Access', 'warning');
            return redirect(route('admin.dashboard'));
        }
        $log = ActivityLog::findOrFail($id);
        return view('admin.activity_logs.show', compact('log'));
    }
}
