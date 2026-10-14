<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
use RealRashid\SweetAlert\Facades\Alert;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }
        $user = Auth::guard('admin')->user();
        if (isset($user->id) && $user->status == 'inactive') {
            Alert::toast('Your Account Is Not Active Please Contact Admin', 'warning');
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
