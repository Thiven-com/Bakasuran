<?php

namespace App\Http\Controllers\RestaurantPanel;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Display restaurant login page
    public function showLoginForm()
    {
        $email = Cookie::get('restaurant_email', '');

        return view('restaurantpanel.auth.login', compact('email'));
    }

    // Restaurant login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        

        $remember = $request->boolean('remember');
        $credentials['is_active'] = true;

        $restaurant = Restaurant::where('email', $request->email)->first();

        

        if (Auth::guard('restaurant')->attempt($credentials, $remember)) {

            $request->session()->regenerate();

            if ($remember) {
                Cookie::queue(
                    Cookie::make('restaurant_email', $request->email, 43200)
                );
            } else {
                Cookie::queue(Cookie::forget('restaurant_email'));
            }

            return redirect()->route('restaurant.dashboard');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid credentials or inactive restaurant account.',
            ])
            ->onlyInput('email');
    }

    // Restaurant logout
    public function logout(Request $request)
    {
        Auth::guard('restaurant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('restaurant_email'));

        return redirect()->route('restaurant.login');
    }

    // Display forgot password page
    public function showForgotForm()
    {
        return view('restaurantpanel.auth.forgot-password');
    }

    // Send OTP to restaurant email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:restaurants,email',
        ]);

        $restaurant = Restaurant::where('email', $request->email)->firstOrFail();

        $otp = (string) random_int(100000, 999999);

        // Store OTP as plain text (for development)
        $restaurant->otp = $otp;
        $restaurant->save();

        // Send OTP email
        try {
            Mail::raw(
                "Your BAKASURAN Restaurant Panel password reset OTP is: {$otp}",
                function ($message) use ($request) {
                    $message->to($request->email)
                        ->subject('Restaurant Panel Password Reset OTP');
                }
            );
        } catch (\Exception $e) {
            Log::info('Restaurant OTP email error: ' . $e->getMessage());
        }

        session(['email' => $request->email]);

        return redirect()
            ->route('restaurant.password.verifyForm')
            ->with('success', 'OTP generated. Please check your email.');
    }

    // Display OTP verification page
    public function showVerifyForm()
    {
        if (!session()->has('email')) {
            return redirect()->route('restaurant.password.request');
        }

        return view('restaurantpanel.auth.verify-otp');
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $restaurant = Restaurant::where('email', $request->email)->first();

        if (
            !$restaurant ||
            !$restaurant->otp ||
            $request->otp !== $restaurant->otp
        ) {
            return back()->withErrors([
                'otp' => 'Invalid OTP. Please try again.',
            ]);
        }

        // Mark OTP verification in session
        session([
            'email' => $restaurant->email,
            'restaurant_otp_verified' => true,
        ]);

        return view('restaurantpanel.auth.reset-password', [
            'email' => $restaurant->email,
        ]);
    }

    // Reset restaurant password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:restaurants,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $restaurant = Restaurant::where('email', $request->email)->firstOrFail();

        if (
            !session()->has('email') ||
            session('email') !== $restaurant->email ||
            !session('restaurant_otp_verified') ||
            !$restaurant->otp
        ) {
            return redirect()
                ->route('restaurant.password.request')
                ->withErrors([
                    'email' => 'Please verify your OTP first.',
                ]);
        }

        // Password is automatically hashed by the Restaurant model cast
        $restaurant->password = $request->password;
        $restaurant->otp = null;
        $restaurant->save();

        session()->forget([
            'email',
            'restaurant_otp_verified',
        ]);

        return redirect()
            ->route('restaurant.login')
            ->with('success', 'Password reset successfully. Please login.');
    }
}
