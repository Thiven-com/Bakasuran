<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    // Get Site Settings
    public function index()
    {
        $settings = SiteSetting::first();

        if (!$settings) {
            return response()->json([
                'status' => false,
                'message' => 'Site settings not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Site settings fetched successfully',
            'data' => $settings
        ], 200);
    }
}
