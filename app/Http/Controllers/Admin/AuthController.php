<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Seller;
use Carbon\Carbon;
use Validator;
use Alert;
use App\Mail\AdminPasswordResetOtpMail;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\SiteSetting;
use DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $this->updateOrders();
        // site setting for footer
        $site = SiteSetting::first();

        // Orders today count
        $ordersCount = Order::where('order_type', 'order')->where('payment_status', 'paid')->whereDate('created_at', Carbon::today())->count();

        // basic counts
        $counts = [
            'customers' => Customer::count(),
            'suppliers' => 0, // adapt if you have suppliers model
            'orders' => Order::where('order_type', 'order')->where('payment_status', 'paid')->count(),
            'categories' => Category::count(),
            'products' => Product::count(),
        ];

        // Totals placeholders (replace with your actual calculations)
        $totals = [
            'sales' => (float) Order::where('order_type', 'order')->where('payment_status', 'paid')->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('grand_total'),
            'sales_change_percent' => 22,
            'sales_return' => 0,
            'purchase' => 0,
            'purchase_return' => 0,
            'profit' => 0,
            'profit_change_percent' => 12,
            'invoice_due' => 0,
            'expense' => 0,
            'payment_returns' => 0,
        ];

        // Low stock variants
        $lowStockThreshold = 10;
        $lowStockProducts = ProductVariant::with('product')
            ->where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock', 'asc')
            ->take(6)
            ->get()
            ->map(function ($v) {
                return (object) [
                    'product_id' => $v->product_id,
                    'title' => $v->product?->title,
                    'image' => $v->image,
                    'sku' => $v->sku,
                    'stock' => $v->stock,
                    'id' => $v->id,
                ];
            });

        $lowStockAlert = $lowStockProducts->first();

        // $topSelling = collect(); 
        $topSelling = OrderItem::selectRaw('product_variant_id,product_title,unit_price,SUM(quantity) as total_quantity,SUM(subtotal) as total_price')
            ->groupBy('product_variant_id', 'product_title', 'unit_price')
            ->orderByDesc('total_quantity')
            ->take(6)
            ->get();
        // Recent sales (last 6 orders)
        $recentSales = Order::where('order_type', 'order')->where('payment_status', 'paid')->with('user')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($o) {
                return (object) [
                    'id' => $o->id,
                    'customer_name' => $o->user?->name ?? 'Guest',
                    'customer_avatar' => $o->user?->avatar ?? null,
                    'grand_total' => $o->grand_total,
                    'status' => $o->status,
                    'created_at' => $o->created_at,
                ];
            });

        // Top categories
        $topCategories = Category::withCount('products')->orderByDesc('products_count')->take(6)->get();

        // chart summary placeholder
        $chartSummary = [
            'purchase_count' => 3000,
            'sales_count' => 1000,
        ];

        // customers overview placeholder
        $customersOverview = [
            'first_time' => 5500,
            'first_time_change' => '+25%',
            'returning' => 3500,
            'returning_change' => '+21%',
        ];

        $topCustomers = DB::table('customers')
            ->select(
                'customers.id',
                'customers.name',
                'customers.email',
                'customers.profile_pic as avatar',
                // 'customers.country',
                DB::raw('(select count(*) from `orders` where `customers`.`id` = `orders`.`customer_id`) as orders_count'),
                DB::raw('(select sum(`orders`.`grand_total`) from `orders` where `customers`.`id` = `orders`.`customer_id`) as orders_sum_grand_total')
            )
            ->whereNull('customers.deleted_at')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();
        $today = Carbon::today();

        // Today's Sales
        $todayOrders = Order::whereDate('created_at', $today)->where('payment_status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        // Today's Transactions
        $todayTransactions = Payment::whereDate('created_at', $today)->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();


        $rawData = Order::where('payment_status', 'paid')->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('DAY(created_at) as day, SUM(grand_total) as total')
            ->groupBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $daysInMonth = Carbon::now()->daysInMonth;

        $data['month_dates'] = [];
        $data['month_values'] = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $data['month_dates'][] = (string) $i;
            $data['month_values'][] = $rawData[$i] ?? 0;
        }
        $data['month_total'] = array_sum($data['month_values']);
        $data['today_total'] = $rawData[now()->day] ?? 0;
        $data['peak_day'] = max($data['month_values']);

        $pendingJobs = DB::table('jobs')->count();
        // $data['average'] = round($data['month_total'] / count($data['month_values']));

        // Prepare compact variables for the view
        return view('admin.auth.dashboard', [
            'site' => $site,
            'ordersCount' => $ordersCount,
            'counts' => $counts,
            'totals' => $totals,
            'lowStockProducts' => $lowStockProducts,
            'lowStockAlert' => $lowStockAlert,
            'lowStockThreshold' => $lowStockThreshold,
            'topSelling' => $topSelling,
            'recentSales' => $recentSales,
            'topCategories' => $topCategories,
            'chartSummary' => $chartSummary,
            'customersOverview' => $customersOverview,
            'topCustomers' => $topCustomers,
            'todayOrders' => $todayOrders,
            'todayTransactions' => $todayTransactions,
            'data' => $data,
            'pendingJobs' => $pendingJobs
        ]);
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
        \Artisan::call('queue:restart');

        return response()->json([
            'status' => true,
            'message' => 'Queue restarted successfully'
        ]);
    }
}
