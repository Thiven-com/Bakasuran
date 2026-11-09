<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Seller;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use App\Mail\AdminPasswordResetOtpMail;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        $email = Cookie::get('admin_email');
        $password = Cookie::get('admin_password')
            ? Crypt::decryptString(Cookie::get('admin_password'))
            : '';

        return view('admin.auth.login', compact('email', 'password'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {

            if ($remember) {

                Cookie::queue(
                    Cookie::make('admin_email', $request->email, 43200) // 30 days
                );

                Cookie::queue(
                    Cookie::make(
                        'admin_password',
                        Crypt::encryptString($request->password),
                        43200
                    )
                );
            } else {
                Cookie::queue(Cookie::forget('admin_email'));
                Cookie::queue(Cookie::forget('admin_password'));
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors(['Invalid Credentials']);
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        /*
        |--------------------------------------------------------------------------
        | Helper: Find an existing table
        |--------------------------------------------------------------------------
        */
        $findTable = function (array $tables) {
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    return $table;
                }
            }

            return null;
        };

        /*
        |--------------------------------------------------------------------------
        | Helper: Find an existing column
        |--------------------------------------------------------------------------
        */
        $findColumn = function ($table, array $columns) {
            if (!$table) {
                return null;
            }

            foreach ($columns as $column) {
                if (Schema::hasColumn($table, $column)) {
                    return $column;
                }
            }

            return null;
        };

        /*
        |--------------------------------------------------------------------------
        | Main Counts
        |--------------------------------------------------------------------------
        */
        $counts = [
            'restaurants' => Schema::hasTable('restaurants')
                ? DB::table('restaurants')->count()
                : 0,

            'tables' => Schema::hasTable('restaurant_tables')
                ? DB::table('restaurant_tables')->count()
                : 0,

            'menu_items' => Schema::hasTable('products')
                ? DB::table('products')->count()
                : 0,

            'categories' => Schema::hasTable('categories')
                ? DB::table('categories')->count()
                : 0,

            'variants' => Schema::hasTable('product_variants')
                ? DB::table('product_variants')->count()
                : 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | Restaurant Tables
        |--------------------------------------------------------------------------
        */
        $tableQuery = Schema::hasTable('restaurant_tables')
            ? DB::table('restaurant_tables')
            : null;

        $restaurantTables = $tableQuery
            ? $tableQuery->orderBy('id', 'desc')->take(8)->get()
            : collect();

        $activeTables = 0;
        $inactiveTables = 0;

        if (Schema::hasTable('restaurant_tables')) {
            if (Schema::hasColumn('restaurant_tables', 'status')) {
                $activeTables = DB::table('restaurant_tables')
                    ->where('status', 'active')
                    ->count();

                $inactiveTables = DB::table('restaurant_tables')
                    ->where('status', '!=', 'active')
                    ->count();
            } elseif (Schema::hasColumn('restaurant_tables', 'is_active')) {
                $activeTables = DB::table('restaurant_tables')
                    ->where('is_active', 1)
                    ->count();

                $inactiveTables = DB::table('restaurant_tables')
                    ->where('is_active', 0)
                    ->count();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Booking Table Detection
        |--------------------------------------------------------------------------
        */
        $bookingTable = $findTable([
            'restaurant_bookings',
            'table_bookings',
            'bookings',
        ]);

        $bookingDateColumn = $findColumn($bookingTable, [
            'booking_date',
            'reservation_date',
            'date',
            'created_at',
        ]);

        $bookingStatusColumn = $findColumn($bookingTable, [
            'status',
            'booking_status',
        ]);

        $bookingsToday = 0;
        $totalBookings = 0;
        $pendingBookings = 0;
        $recentBookings = collect();

        if ($bookingTable) {
            $totalBookings = DB::table($bookingTable)->count();

            if ($bookingDateColumn) {
                $bookingsToday = DB::table($bookingTable)
                    ->whereDate($bookingDateColumn, $today)
                    ->count();
            }

            if ($bookingStatusColumn) {
                $pendingBookings = DB::table($bookingTable)
                    ->whereRaw('LOWER(`' . $bookingStatusColumn . '`) = ?', ['pending'])
                    ->count();
            }

            $recentBookings = DB::table($bookingTable)
                ->orderByDesc(
                    Schema::hasColumn($bookingTable, 'created_at')
                    ? 'created_at'
                    : 'id'
                )
                ->take(6)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Order Table Detection
        |--------------------------------------------------------------------------
        */
        $orderTable = $findTable([
            'restaurant_orders',
            'orders',
        ]);

        $orderDateColumn = $findColumn($orderTable, [
            'created_at',
            'order_date',
        ]);

        $orderAmountColumn = $findColumn($orderTable, [
            'grand_total',
            'total_amount',
            'total',
            'amount',
        ]);

        $orderStatusColumn = $findColumn($orderTable, [
            'status',
            'order_status',
        ]);

        $paymentStatusColumn = $findColumn($orderTable, [
            'payment_status',
        ]);

        $ordersToday = 0;
        $totalOrders = 0;
        $todayRevenue = 0;
        $monthRevenue = 0;
        $recentOrders = collect();

        if ($orderTable) {
            $totalOrders = DB::table($orderTable)->count();

            if ($orderDateColumn) {
                $ordersToday = DB::table($orderTable)
                    ->whereDate($orderDateColumn, $today)
                    ->count();

                $recentOrders = DB::table($orderTable)
                    ->orderByDesc($orderDateColumn)
                    ->take(6)
                    ->get();

                if ($orderAmountColumn) {
                    $todayRevenue = DB::table($orderTable)
                        ->whereDate($orderDateColumn, $today)
                        ->when($paymentStatusColumn, function ($query) use ($paymentStatusColumn) {
                            $query->where($paymentStatusColumn, 'paid');
                        })
                        ->sum($orderAmountColumn);

                    $monthRevenue = DB::table($orderTable)
                        ->whereBetween($orderDateColumn, [
                            $startOfMonth,
                            $endOfMonth,
                        ])
                        ->when($paymentStatusColumn, function ($query) use ($paymentStatusColumn) {
                            $query->where($paymentStatusColumn, 'paid');
                        })
                        ->sum($orderAmountColumn);
                }
            } else {
                $recentOrders = DB::table($orderTable)
                    ->orderByDesc('id')
                    ->take(6)
                    ->get();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Monthly Booking Chart
        |--------------------------------------------------------------------------
        */
        $daysInMonth = Carbon::now()->daysInMonth;

        $chartDates = [];
        $chartValues = [];

        $bookingChartData = [];

        if ($bookingTable && $bookingDateColumn) {
            $bookingChartData = DB::table($bookingTable)
                ->whereBetween($bookingDateColumn, [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString(),
                ])
                ->selectRaw(
                    'DAY(' . $bookingDateColumn . ') as booking_day, COUNT(*) as total'
                )
                ->groupByRaw('DAY(' . $bookingDateColumn . ')')
                ->pluck('total', 'booking_day')
                ->toArray();
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $chartDates[] = (string) $day;
            $chartValues[] = (int) ($bookingChartData[$day] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Restaurant Booking Configuration
        |--------------------------------------------------------------------------
        */
        $bookingSettingsCount = Schema::hasTable('restaurant_booking_settings')
            ? DB::table('restaurant_booking_settings')
                ->where('is_booking_enabled', 1)
                ->count()
            : 0;

        $bookingSessionsCount = Schema::hasTable('restaurant_booking_sessions')
            ? DB::table('restaurant_booking_sessions')
                ->where('is_active', 1)
                ->count()
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Dashboard Data
        |--------------------------------------------------------------------------
        */
        $site = Schema::hasTable('site_settings')
            ? DB::table('site_settings')->first()
            : null;

        $data = [
            'month_total' => $monthRevenue,
            'today_total' => $todayRevenue,
            'month_dates' => $chartDates,
            'month_values' => $chartValues,
            'peak_day' => max($chartValues ?: [0]),
        ];

        return view('admin.auth.dashboard', compact(
            'site',
            'counts',
            'restaurantTables',
            'activeTables',
            'inactiveTables',
            'bookingsToday',
            'totalBookings',
            'pendingBookings',
            'recentBookings',
            'ordersToday',
            'totalOrders',
            'todayRevenue',
            'monthRevenue',
            'recentOrders',
            'bookingSettingsCount',
            'bookingSessionsCount',
            'data'
        ));
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function sellers(Request $request)
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


    public function sellers_store(Request $request)
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
        return redirect(route('admin.sellers'));
    }

    public function showForgotForm()
    {
        return view('admin.auth.forgot-password');
    }
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email'
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if (!isset($admin->id)) {
            return back()->withErrors(['email' => 'Invalid Email']);
        }
        // $otp = 123456;
        $otp = rand(100000, 999999);

        $admin->otp = $otp;
        $admin->save();
        try {
            Mail::to($request->email)->send(new AdminPasswordResetOtpMail($otp));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        session(['email' => $request->email]);
        return redirect()->route('admin.password.verifyForm')
            ->with('email', $request->email)
            ->with('success', 'OTP sent to your email.');
    }
    public function showVerifyForm()
    {
        if (!session('email')) {
            return redirect()->route('admin.password.request');
        }

        return view('admin.auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !$admin->otp) {
            session(['email' => $request->email]);

            return redirect(route('admin.password.verifyForm'))->withErrors(['otp' => 'Invalid OTP']);
        }

        if ($request->otp != $admin->otp) {
            session(['email' => $request->email]);

            return redirect(route('admin.password.verifyForm'))->withErrors(['otp' => 'Invalid OTP']);
        }

        return view('admin.auth.reset-password', [
            'email' => $request->email
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);


        $admin = Admin::where('email', $request->email)->first();

        $admin->password = bcrypt($request->password);
        $admin->otp = null;
        $admin->save();
        session()->forget('email');

        return redirect()->route('admin.login')
            ->with('success', 'Password reset successfully.');
    }

    public function updateOrders()
    {
        // $orders = Order::where('payment_status', 'pending')->where('status', '!=', 'cancelled')
        //     ->where('created_at', '<=', Carbon::now()->subHour())
        //     ->get();

        $orders = Order::where('payment_status', 'pending')->where('status', '!=', 'cancelled')
            ->where('created_at', '<=', Carbon::now()->subMinutes(15))
            ->get();

        foreach ($orders as $order) {

            $orderItems = OrderItem::where('order_id', $order->id)->get();

            foreach ($orderItems as $orderItem) {
                $variant = ProductVariant::find($orderItem->product_variant_id);

                if ($variant) {
                    if ($order->order_type == 'preorder') {
                        $variant->stock += $orderItem->quantity;
                    } else {
                        $variant->stock += $orderItem->quantity;
                    }
                    $variant->save();
                } else {
                    Log::info("Variant Not Found" . $orderItem->product_variant_id);
                    Log::info("Variant SKU" . $orderItem->sku);
                }
            }

            // Update order status
            $order->status = 'cancelled'; // or 'failed'
            $order->payment_status = 'failed'; // or 'failed'
            $order->save();
        }

        Log::info(count($orders) . " Updated Successfully");

        return response()->json([
            'success' => 1,
            'message' => "Updated Successfully",
            'count' => count($orders)
        ]);
    }
    public function backupDatabase()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');

        $fileName = 'ssh_backup_' . now()->format('Y_m_d_H_i_s') . '.sql';

        $backupFolder = public_path('backups');

        if (!file_exists($backupFolder)) {
            mkdir($backupFolder, 0777, true);
        }

        $path = $backupFolder . DIRECTORY_SEPARATOR . $fileName;

        // Windows / Linux
        // if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        //     $mysqldump = '"C:\\Program Files\\MySQL\\MySQL Server 9.6\\bin\\mysqldump.exe"';
        // } else {
        //     $mysqldump = '/bin/mysqldump';
        // }

        // Safe dump command
        // $command = sprintf(
        //     '%s --user=%s --password=%s --host=%s --single-transaction %s > %s 2>&1',
        //     $mysqldump,
        //     escapeshellarg($username),
        //     escapeshellarg($password),
        //     escapeshellarg($host),
        //     escapeshellarg($database),
        //     escapeshellarg($path)
        // );

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            $mysqldump = '"C:\\Program Files\\MySQL\\MySQL Server 9.6\\bin\\mysqldump.exe"';

            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --set-gtid-purged=OFF --single-transaction --skip-add-drop-table --skip-lock-tables --skip-add-locks --skip-disable-keys --skip-comments --compact --complete-insert %s > %s 2>NUL',
                $mysqldump,
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($path)
            );

        } else {

            $mysqldump = '/bin/mysqldump';

            $command = sprintf(
                '%s --user=%s --password=%s --host=%s --set-gtid-purged=OFF --single-transaction --skip-add-drop-table --skip-lock-tables --skip-add-locks --skip-disable-keys --skip-comments --compact --complete-insert %s > %s 2>/dev/null',
                $mysqldump,
                escapeshellarg($username),
                escapeshellarg($password),
                escapeshellarg($host),
                escapeshellarg($database),
                escapeshellarg($path)
            );
        }

        // Execute command
        exec($command, $output, $result);

        // If failed
        if ($result !== 0) {

            Log::error('Database backup failed', [
                'command' => $command,
                'result_code' => $result,
                'output' => $output,
                'database' => $database,
                'host' => $host,
                'time' => now()->toDateTimeString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Database backup failed',
                'output' => $output
            ]);
        }

        Log::info('Database backup success', [
            'file' => $fileName,
            'path' => $path,
        ]);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function queueWork()
    {
        Log::info(PHP_OS);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            // Windows
            pclose(popen("start /B php " . base_path('artisan') . " queue:work", "r"));

        } else {

            // Linux / Server
            exec("nohup php " . base_path('artisan') . " queue:work > /dev/null 2>&1 &");

        }

        return response()->json([
            'status' => true,
            'message' => 'Queue worker started successfully'
        ]);
    }

    public function queueRestart()
    {
        \Illuminate\Support\Facades\Artisan::call('queue:restart');

        return response()->json([
            'status' => true,
            'message' => 'Queue restarted successfully'
        ]);
    }
}
