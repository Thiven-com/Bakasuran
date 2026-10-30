
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        $guard = Auth::guard('admin');

        // Check admin authentication
        if (!$guard->check()) {
            return redirect()->route('admin.login');
        }

        $user = $guard->user();

        // Check admin account status
        if ($user->status === 'inactive') {
            Auth::guard('admin')->logout();

            Alert::toast(
                'Your Account Is Not Active. Please Contact Admin.',
                'warning'
            );

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
