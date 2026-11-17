<?php

namespace App\Http\Controllers\RestaurantPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use App\Models\RestaurantPanel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Display restaurant login page.
     */
    public function showLoginForm()
    {
        $email = Cookie::get('restaurant_email');

        $password = Cookie::get('restaurant_password')
            ? Crypt::decryptString(Cookie::get('restaurant_password'))
            : '';

        return view('restaurantpanel.auth.login', compact(
            'email',
            'password'
        ));
    }

    /**
     * Handle restaurant login.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $remember = $request->has('remember');

        if (Auth::guard('restaurant')->attempt($credentials, $remember)) {

            if ($remember) {

                Cookie::queue(
                    Cookie::make(
                        'restaurant_email',
                        $request->email,
                        43200
                    )
                );

                Cookie::queue(
                    Cookie::make(
                        'restaurant_password',
                        Crypt::encryptString($request->password),
                        43200
                    )
                );

            } else {

                Cookie::queue(Cookie::forget('restaurant_email'));

                Cookie::queue(Cookie::forget('restaurant_password'));
            }

            $request->session()->regenerate();

            return redirect()->route('restaurant.dashboard');
        }

        return redirect()->back()
            ->withErrors(['Invalid Credentials'])
            ->withInput($request->only('email'));
    }

    /**
     * Restaurant logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('restaurant')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('restaurant_email'));

        Cookie::queue(Cookie::forget('restaurant_password'));

        return redirect()->route('restaurant.login');
    }

    public function showForgotForm()
    {
        return view('restaurantpanel.auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:restaurantpanel,email',
        ]);

        $restaurant = RestaurantPanel::where('email', $request->email)->first();

        $otp = rand(100000, 999999);

        $restaurant->otp = $otp;
        $restaurant->save();

        Mail::raw(
            "Your BAKASURAN Restaurant Panel password reset OTP is: {$otp}",
            function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Restaurant Panel Password Reset OTP');
            }
        );

        session(['email' => $request->email]);

        return redirect()
            ->route('restaurant.password.verifyForm')
            ->with('success', 'OTP sent successfully to your email.');
    }

    public function showVerifyForm()
    {
        if (!session()->has('email')) {
            return redirect()->route('restaurant.password.request');
        }

        return view('restaurantpanel.auth.verify-otp');
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $restaurant = RestaurantPanel::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$restaurant) {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.',
            ]);
        }

        session(['email' => $restaurant->email]);

        return view('restaurantpanel.auth.reset-password', [
            'email' => $restaurant->email,
        ]);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:restaurantpanel,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $restaurant = RestaurantPanel::where('email', $request->email)->first();

        $restaurant->password = Hash::make($request->password);
        $restaurant->otp = null;
        $restaurant->save();

        session()->forget('email');

        return redirect()
            ->route('restaurant.login')
            ->with('success', 'Password reset successfully. Please login.');
    }
}
