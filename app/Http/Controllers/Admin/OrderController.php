<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Exports\SkuOrderReportExport;
use App\Exports\TodayOrdersExport;
use App\Exports\TodayTransactionsExport;
use App\Http\Controllers\Controller;
use App\Jobs\CreateDTDCParcelJob;
use App\Jobs\CreateParcelJob;
use App\Models\Customer;
use App\Services\Order\DTDCService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderNote;
use App\Models\Payment;
use App\Models\OrderShipment;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use App\Models\State;
use App\Services\Order\DelhiveryService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    /**
     * Display listing of orders (admin).
     * Route example: Route::get('admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
     */
    protected $delhivery;
    public function __construct(DelhiveryService $delhivery)
    {
        $this->delhivery = $delhivery;
    }
    public function index(Request $request)
    {
        // filters
        $perPage = (int) $request->get('per_page', 1000);
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status'); // e.g. pending, completed, cancelled
        $paymentStatus = $request->get('payment_status') ?? ''; // paid, pending
        $customerId = $request->get('customer_id');
        $from = $request->get('from'); // yyyy-mm-dd
        $to = $request->get('to');

        // base query
        $query = Order::where('order_type', 'order')
            ->with([
                // only columns needed for list preview
                'items' => function ($q) {
                    $q->select('id', 'order_id', 'product_title', 'quantity', 'unit_price', 'product_variant_id')
                        ->orderBy('id', 'asc');
                },
                'user:id,name,email', // assuming order->user relation for customer
                'payments:id,order_id,amount,status,method,created_at',
            ])
            ->select(['orders.*'])
            ->latest('orders.id');

        if ($customerId) {
            $query->where('user_id', (int) $customerId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }

        if (!empty($from)) {
            try {
                $query->whereDate('created_at', '>=', Carbon::parse($from)->toDateString());
            } catch (\Throwable $e) {
            }
        }
        if (!empty($to)) {
            try {
                $query->whereDate('created_at', '<=', Carbon::parse($to)->toDateString());
            } catch (\Throwable $e) {
            }
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_id', 'like', "%{$search}%")
                    ->orWhere('id', (int) $search)
                    ->orWhere('shipping_address->name', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qq) => $qq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")->orWhere('mobile', 'like', "%{$search}%"));
            });
        }
        $data['order_amount'] = (clone $query)->where('payment_status', 'paid')->sum('grand_total');

        // paginate
        $orders = $query->paginate($perPage)->appends($request->query());

        // Pass to blade view (admin.sales.index)
        return view('admin.orders.orders', compact('orders', 'data'));
    }

    public function preorders(Request $request)
    {
        // filters
        $perPage = (int) $request->get('per_page', 1000);
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status'); // e.g. pending, completed, cancelled
        $paymentStatus = $request->get('payment_status') ?? ''; // paid, pending
        $customerId = $request->get('customer_id');
        $from = $request->get('from'); // yyyy-mm-dd
        $to = $request->get('to');

        // base query
        $query = Order::where('order_type', 'preorder')
            ->with([
                // only columns needed for list preview
                'items' => function ($q) {
                    $q->select('id', 'order_id', 'product_title', 'quantity', 'unit_price', 'product_variant_id')
                        ->orderBy('id', 'asc');
                },
                'user:id,name,email', // assuming order->user relation for customer
                'payments:id,order_id,amount,status,method,created_at',
            ])
            ->select(['orders.*'])
            ->latest('orders.id');

        if ($customerId) {
            $query->where('user_id', (int) $customerId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }

        if (!empty($from)) {
            try {
                $query->whereDate('created_at', '>=', Carbon::parse($from)->toDateString());
            } catch (\Throwable $e) {
            }
        }
        if (!empty($to)) {
            try {
                $query->whereDate('created_at', '<=', Carbon::parse($to)->toDateString());
            } catch (\Throwable $e) {
            }
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_id', 'like', "%{$search}%")
                    ->orWhere('id', (int) $search)
                    ->orWhere('shipping_address->name', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qq) => $qq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }
        $data['order_amount'] = (clone $query)->where('payment_status', 'paid')->sum('grand_total');

        // paginate
        $orders = $query->paginate($perPage)->appends($request->query());
        return view('admin.orders.preorders', compact('orders', 'data'));
    }
    public function manualorders(Request $request)
    {
        // filters
        $perPage = (int) $request->get('per_page', 1000);
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status'); // e.g. pending, completed, cancelled
        $paymentStatus = $request->get('payment_status') ?? ''; // paid, pending
        $customerId = $request->get('customer_id');
        $from = $request->get('from'); // yyyy-mm-dd
        $to = $request->get('to');

        // base query
        $query = Order::where('order_type', 'manual_order')
            ->with([
                // only columns needed for list preview
                'items' => function ($q) {
                    $q->select('id', 'order_id', 'product_title', 'quantity', 'unit_price', 'product_variant_id')
                        ->orderBy('id', 'asc');
                },
                'user:id,name,email', // assuming order->user relation for customer
                'payments:id,order_id,amount,status,method,created_at',
            ])
            ->select(['orders.*'])
            ->latest('orders.id');

        if ($customerId) {
            $query->where('user_id', (int) $customerId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($paymentStatus)) {
            $query->where('payment_status', $paymentStatus);
        }

        if (!empty($from)) {
            try {
                $query->whereDate('created_at', '>=', Carbon::parse($from)->toDateString());
            } catch (\Throwable $e) {
            }
        }
        if (!empty($to)) {
            try {
                $query->whereDate('created_at', '<=', Carbon::parse($to)->toDateString());
            } catch (\Throwable $e) {
            }
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_id', 'like', "%{$search}%")
                    ->orWhere('id', (int) $search)
                    ->orWhere('shipping_address->name', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($qq) => $qq->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        $data['order_amount'] = (clone $query)->where('payment_status', 'paid')->sum('grand_total');
        // paginate
        $orders = $query->paginate($perPage)->appends($request->query());
        return view('admin.orders.manual_orders', compact('orders', 'data'));
    }

    public function skuReport(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $date = $request->get('date');

        // 📅 Date filter
        $dateFilter = '';
        if (!empty($date)) {
            try {
                $parsedDate = \Carbon\Carbon::parse($date)->toDateString();
            } catch (\Throwable $e) {
                $parsedDate = now()->toDateString();
            }
        } else {
            $parsedDate = now()->toDateString();
        }

        // 🔍 Search filter
        $searchFilter = '';
        if ($search !== '') {
            $searchFilter = " AND (
            pv.sku LIKE '%{$search}%'
            OR oi.product_title LIKE '%{$search}%'
            OR o.invoice_id LIKE '%{$search}%'
            OR o.id = '" . (int) $search . "'
        ) ";
        }

        // 🚀 MAIN QUERY (20 min gap batching)
        $data = DB::select("
        SELECT 
            t.batch_no,
            MIN(t.created_at) as batch_time,
            t.sku,
            MAX(t.product_title) as product_title,
            COUNT(DISTINCT t.order_id) as order_count,
            SUM(t.quantity) as total_qty
        FROM (
            SELECT 
                osh.created_at,
                osh.order_id,
                oi.quantity,
                oi.product_title,
                pv.sku,

                @batch_no := IF(
                    @prev_time IS NULL,
                    1,
                    IF(
                        TIMESTAMPDIFF(MINUTE, @prev_time, osh.created_at) <= 20,
                        @batch_no,
                        @batch_no + 1
                    )
                ) as batch_no,

                @prev_time := osh.created_at

            FROM order_status_histories osh
            JOIN orders o ON osh.order_id = o.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN product_variants pv ON oi.product_variant_id = pv.id,
            (SELECT @batch_no := 0, @prev_time := NULL) vars

            WHERE osh.status = 'shipping'
            AND o.order_type = 'order'
            AND DATE(osh.created_at) = '{$parsedDate}'
            $searchFilter

            ORDER BY osh.created_at ASC
        ) t
        GROUP BY t.batch_no, t.sku
        ORDER BY batch_time DESC
    ");

        // Convert to collection
        $skus = collect($data);

        return view('admin.orders.sku_report', compact('skus'));
    }

    // public function skuReport(Request $request)
    // {
    //     $perPage = (int) $request->get('per_page', 1000);
    //     $search = trim((string) $request->get('search', ''));
    //     $date = $request->get('date'); // single date

    //     $query = DB::table('order_items')
    //         ->join('orders', 'order_items.order_id', '=', 'orders.id')
    //         ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
    //         ->select(
    //             'product_variants.sku',
    //             DB::raw('MAX(order_items.product_title) as product_title'),
    //             DB::raw('COUNT(DISTINCT orders.id) as order_count'),
    //             DB::raw('SUM(order_items.quantity) as total_qty')
    //         )
    //         ->where('orders.order_type', 'order')
    //         ->whereIn('orders.status', ['shipped', 'shipping']) // ✅ force shipped only
    //         ->groupBy('product_variants.sku');

    //     // 📅 Single Date Filter (default today)
    //     if (!empty($date)) {
    //         try {
    //             $query->whereDate('orders.updated_at', Carbon::parse($date)->toDateString());
    //         } catch (\Throwable $e) {
    //             $query->whereDate('orders.updated_at', now()->toDateString());
    //         }
    //     } else {
    //         $query->whereDate('orders.updated_at', now()->toDateString());
    //     }

    //     // 🔍 Search
    //     if ($search !== '') {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('product_variants.sku', 'like', "%{$search}%")
    //                 ->orWhere('order_items.product_title', 'like', "%{$search}%")
    //                 ->orWhere('orders.invoice_id', 'like', "%{$search}%")
    //                 ->orWhere('orders.id', (int) $search);
    //         });
    //     }

    //     // 📄 Pagination
    //     $skus = $query->orderByDesc('order_count')
    //         ->paginate($perPage)
    //         ->appends($request->query());

    //     return view('admin.orders.sku_report', compact('skus'));
    // }

    // public function skuReport(Request $request)
    // {
    //     $perPage = (int) $request->get('per_page', 1000);
    //     $search = trim((string) $request->get('search', ''));
    //     $date = $request->get('date');

    //     $query = DB::table('order_status_histories')
    //         ->join('orders', 'order_status_histories.order_id', '=', 'orders.id')
    //         ->join('order_items', 'orders.id', '=', 'order_items.order_id')
    //         ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
    //         ->select(
    //             'product_variants.sku',

    //             DB::raw('MAX(order_items.product_title) as product_title'),

    //             // ⏰ Hour Batch
    //             DB::raw("DATE_FORMAT(order_status_histories.created_at, '%Y-%m-%d %H:00:00') as hour_batch"),

    //             // ✅ FIRST TIME in that hour
    //             DB::raw('MIN(order_status_histories.created_at) as first_time'),

    //             // (Optional) LAST TIME
    //             DB::raw('MAX(order_status_histories.created_at) as last_time'),

    //             DB::raw('COUNT(DISTINCT orders.id) as order_count'),
    //             DB::raw('SUM(order_items.quantity) as total_qty')
    //         )
    //         ->where('orders.order_type', 'order')
    //         ->where('order_status_histories.status', 'shipping');

    //     // 📅 Date filter
    //     if (!empty($date)) {
    //         try {
    //             $query->whereDate(
    //                 'order_status_histories.created_at',
    //                 \Carbon\Carbon::parse($date)->toDateString()
    //             );
    //         } catch (\Throwable $e) {
    //             $query->whereDate('order_status_histories.created_at', now()->toDateString());
    //         }
    //     } else {
    //         $query->whereDate('order_status_histories.created_at', now()->toDateString());
    //     }

    //     // 🔍 Search
    //     if ($search !== '') {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('product_variants.sku', 'like', "%{$search}%")
    //                 ->orWhere('order_items.product_title', 'like', "%{$search}%")
    //                 ->orWhere('orders.invoice_id', 'like', "%{$search}%")
    //                 ->orWhere('orders.id', (int) $search);
    //         });
    //     }

    //     // 📊 Grouping
    //     $query->groupBy('product_variants.sku', 'hour_batch');

    //     // 🔽 Sorting
    //     $skus = $query
    //         ->orderBy('product_variants.sku')
    //         ->orderByDesc('hour_batch')
    //         ->paginate($perPage)
    //         ->appends($request->query());

    //     return view('admin.orders.sku_report', compact('skus'));
    // }

    public function exportSkuReport(Request $request)
    {
        return Excel::download(
            new SkuOrderReportExport($request),
            'sku_report_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // public function skuReport(Request $request)
    // {
    //     $perPage = (int) $request->get('per_page', 1000);
    //     $status = $request->get('status') ?? 'placed';
    //     $paymentStatus = $request->get('payment_status') ?? 'paid';
    //     $customerId = $request->get('customer_id');
    //     $from = $request->get('from');
    //     $to = $request->get('to');
    //     $search = trim((string) $request->get('search', ''));

    //     $query = DB::table('order_items')
    //         ->join('orders', 'order_items.order_id', '=', 'orders.id')
    //         ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
    //         ->select(
    //             'product_variants.sku',
    //             DB::raw('MAX(order_items.product_title) as product_title'),
    //             DB::raw('COUNT(DISTINCT orders.id) as order_count'),
    //             DB::raw('SUM(order_items.quantity) as total_qty')
    //         )
    //         ->where('orders.order_type', 'order')
    //         ->groupBy('product_variants.sku');

    //     // 🔎 Filters (same as your order list)

    //     if ($customerId) {
    //         $query->where('orders.user_id', (int)$customerId);
    //     }

    //     if (!empty($status)) {
    //         $query->where('orders.status', $status);
    //     }

    //     if (!empty($paymentStatus)) {
    //         $query->where('orders.payment_status', $paymentStatus);
    //     }

    //     if (!empty($from)) {
    //         try {
    //             $query->whereDate('orders.created_at', '>=', Carbon::parse($from)->toDateString());
    //         } catch (\Throwable $e) {
    //         }
    //     }

    //     if (!empty($to)) {
    //         try {
    //             $query->whereDate('orders.created_at', '<=', Carbon::parse($to)->toDateString());
    //         } catch (\Throwable $e) {
    //         }
    //     }

    //     if ($search !== '') {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('product_variants.sku', 'like', "%{$search}%")
    //                 ->orWhere('orders.invoice_id', 'like', "%{$search}%")
    //                 ->orWhere('orders.id', (int)$search);
    //         });
    //     }

    //     // 📄 Pagination
    //     $skus = $query->orderByDesc('order_count')
    //         ->paginate($perPage)
    //         ->appends($request->query());

    //     return view('admin.orders.sku_report', [
    //         'skus' => $skus
    //     ]);
    // }

    /**
     * Return JSON details for a single order (for AJAX modal)
     * Route: Route::get('admin/orders/{order}', [OrderController::class,'show'])->name('admin.orders.show');
     */
    public function show(Request $request, Order $order)
    {
        // Eager load everything needed for detail page
        $order->load([
            'items.variant.media',
            'items.variant.attributeValues.attribute',
            'items.variant.product',
            'payments',
            'shipments',
        ]);
        $states = State::get();

        $awb = $order->awb;
        if (!in_array($order->status, ['cancelled', 'returned', 'completed']) && !empty($awb)) {

            try {
                $rawTracking = $this->delhivery->track($awb);
                $tracking = $this->normalizeDelhiveryTracking($rawTracking);

            } catch (\Throwable $e) {
                $tracking = null;
                Log::error("Failed to fetch tracking for order {$order->id}: " . $e->getMessage());
            }

        } else {
            // ❌ Cancelled or Returned → No tracking
            $tracking = null;
        }
        return view('admin.orders.show', compact('order', 'states', 'tracking'));
    }

    // public function updateStatus(Request $request)
    // {
    //     $data = $request->validate([
    //         'status' => 'sometimes|string',
    //         'payment_status' => 'sometimes|string',
    //     ]);
    //     $order = Order::where('id', $request->id)->first();
    //     if (!isset($order->id)) {
    //         Alert::toast('Order Details Not Found', 'warning');
    //         return redirect()->back();
    //     }
    //     DB::beginTransaction();
    //     try {
    //         if (isset($data['status'])) {
    //             $order->status = $data['status'];
    //         }

    //         if (isset($data['payment_status'])) {
    //             $order->payment_status = $data['payment_status'];
    //         }


    //         $order->save();


    //         //order status history - only if status changed (not on every update)
    //         try {
    //             $lastStatus = OrderStatusHistory::where('order_id', $order->id)
    //                 ->latest()
    //                 ->value('status');

    //             // Only insert if status changed
    //             if ($lastStatus !== ($data['status'] ?? null)) {

    //                 $orderstatushistory = new OrderStatusHistory();
    //                 $orderstatushistory->order_id = $order->id;
    //                 $orderstatushistory->status = $data['status'] ?? null;
    //                 $orderstatushistory->remark = 'Status updated to ' . ($data['status'] ?? 'N/A') . ' via admin order update';
    //                 $orderstatushistory->save();
    //             }
    //         } catch (Exception $e) {
    //             Log::info($e->getMessage());
    //         }

    //         $user = Customer::where('id', $order->customer_id)->first();
    //         $name = $user->name ?? 'Customer';
    //         $invoiceId = $order->invoice_id;
    //         $amount = $order->grand_total;
    //         if (isset($user->id)) {
    //             if ($data['status'] == 'shipped') {
    //                 $message = $this->sendWhatsAppMessage(
    //                     $user->mobile,
    //                     'order_shipped_template',
    //                     [
    //                         'field_1' => $name,
    //                         'field_2' => $invoiceId,
    //                         'field_3' => $amount,
    //                     ]
    //                 );
    //                 try {
    //                     $whatsappService = new WhatsAppService();
    //                     $result = $whatsappService->sendTemplateMessage($message);
    //                 } catch (Exception $e) {
    //                     $result = false;
    //                     Log::info($e->getMessage());
    //                 }
    //             } else if ($data['status'] == 'delivered') {
    //                 $message = $this->sendWhatsAppMessage(
    //                     $user->mobile,
    //                     'order_delivered_template',
    //                     [
    //                         'field_1' => $name,
    //                         'field_2' => $invoiceId,
    //                         'field_3' => $amount,
    //                     ]
    //                 );
    //                 try {
    //                     $whatsappService = new WhatsAppService();
    //                     $result = $whatsappService->sendTemplateMessage($message);
    //                 } catch (Exception $e) {
    //                     $result = false;
    //                     Log::info($e->getMessage());
    //                 }
    //             } else if ($data['status'] == 'cancelled') {
    //                 $message = $this->sendWhatsAppMessage(
    //                     $user->mobile,
    //                     'order_cancel_template',
    //                     [
    //                         'field_1' => $name,
    //                         'field_2' => $invoiceId,
    //                         'field_3' => $amount,
    //                     ]
    //                 );
    //                 try {
    //                     $whatsappService = new WhatsAppService();
    //                     $result = $whatsappService->sendTemplateMessage($message);
    //                 } catch (Exception $e) {
    //                     $result = false;
    //                     Log::info($e->getMessage());
    //                 }
    //             }
    //         }


    //         if (!empty($request->payment_status)) {
    //             Payment::where('order_id', $order->id)->update(['status' => $data['payment_status']]);
    //         }


    //         DB::commit();
    //         Alert::toast('Order Updated', 'success');
    //         return redirect()->back();
    //     } catch (\Throwable $e) {
    //         Log::error('Error updating order: ' . $e->getMessage());
    //         DB::rollBack();
    //         Alert::toast('Unable To Update', 'warning');
    //         return redirect()->back();
    //     }
    // }




    public function updateOrder(Request $request)
    {
        $data = $request->validate([
            'customer_note' => 'sometimes|string',
            'order_note' => 'sometimes|string',
            'tracking_link' => 'sometimes|string',
        ]);
        $order = Order::where('id', $request->id)->first();
        if (!isset($order->id)) {
            Alert::toast('Order Details Not Found', 'warning');
            return redirect()->back();
        }
        DB::beginTransaction();
        try {


            if (isset($data['customer_note'])) {
                $order->customer_note = $data['customer_note'];
            }
            if (isset($data['order_note'])) {
                $order->order_note = $data['order_note'];
            }
            if (isset($data['tracking_link'])) {
                $order->tracking_link = $data['tracking_link'];
            }

            $order->save();

            DB::commit();
            Alert::toast('Order Updated', 'success');
            return redirect()->back();
        } catch (\Throwable $e) {
            Log::error('Error updating order: ' . $e->getMessage());
            DB::rollBack();
            Alert::toast('Unable To Update', 'warning');
            return redirect()->back();
        }
    }

    public function destroy(Order $order)
    {
        // decide soft or hard delete - here we hard delete related items/payments/shipments first
        DB::beginTransaction();
        try {
            // remove children (if you store them without FK)
            $order->items()->delete();
            $order->payments()->delete();
            $order->shipments()->delete();

            $order->delete();

            DB::commit();
            return redirect()->route('admin.orders.index', ['payment_status' => 'paid'])->with('success', 'Order deleted');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Unable to delete order: ' . $e->getMessage());
        }
    }

    // OrderController.php

    public function uploadAwbPage()
    {
        return view('admin.orders.upload-awb');
    }

    /**
     * Simple helper to compute paid/due across orders if needed.
     */
    protected function computePaidForOrder(Order $order): float
    {
        return (float) $order->payments()->sum('amount');
    }

    public function createDelhiveryMPS(Request $request, DelhiveryService $delhivery)
    {
        $order = Order::findOrFail($request->order_id);

        $address = (object) $order->shipping_address;

        // ✅ Eager loading
        $order_items = OrderItem::with('variant.product')
            ->where('order_id', $order->id)
            ->get();

        if ($order_items->isEmpty()) {
            return redirect()->back()->with('error', 'No order items found');
        }

        $totalWeight = 0;
        $totalAmount = 0;
        $totalQty = 0;

        $height = 0;
        $width = 0;
        $length = 0;

        foreach ($order_items as $item) {

            $product = $item->variant->product;
            $stock = $item->variant;

            // ✅ Calculate totals
            $itemWeight = ($stock->weight ?? 0) * $item->quantity;
            $totalWeight += $itemWeight;

            $totalAmount += $item->subtotal;
            $totalQty += $item->quantity;

            // ✅ Dimensions (take max)
            $height = max($height, $stock->height ?? 8);
            $width = max($width, $stock->width ?? 25);
            $length = max($length, $stock->length ?? 8);

            // ✅ Build order_items (IMPORTANT)
            $orderItemsPayload[] = [
                "name" => $product->title,
                "sku" => $stock->sku,
                "units" => (int) $item->quantity,
                "selling_price" => (float) $item->unit_price,
            ];
        }

        $pickup = [
            'name' => 'SAMRUDDHI SILK HOUSE',
        ];

        // ✅ SINGLE shipment
        $shipments = [
            [
                'order' => $order->invoice_id,
                'weight' => $totalWeight * 1000, // grams
                'pin' => $address->pincode,
                'add' => $address->address . ',' . $address->address_2 . ',' . $address->landmark,
                'city' => $address->city,
                'state' => $address->state,
                'phone' => $address->mobile,
                'name' => $address->name,
                'payment_mode' => 'Prepaid',
                'total_amount' => $order->grand_total ?? $totalAmount,
                'country' => 'India',

                // ✅ Dimensions
                "shipment_height" => $height ?: 8,
                "shipment_width" => $width ?: 25,
                "shipment_length" => $length ?: 8,
                "shipping_mode" => "Surface",

                'quantity' => $totalQty,

                // ✅ KEY FIX
                // "invoice"          => true,
                // "invoice_amount"   => $order->grand_total,
                // "cod_amount"       => 0,

                // ✅ KEY PART (for label items)
                "order_items" => $orderItemsPayload,

                // ✅ fallback (optional but recommended)
                "products_desc" => implode("\n", array_map(function ($item) {
                    return $item['name'] . " (" . $item['sku'] . ")" . "-" . $item['units'] . " x " . $item['selling_price'] . "=" . $item['units'] * $item['selling_price'];
                }, $orderItemsPayload)),
                // "products_desc" => implode("\n", array_map(function ($item) {
                //     return $item['name'] . " (" . $item['sku'] . ") x" . $item['units'];
                // }, $orderItemsPayload)),
            ]
        ];


        // ✅ Call API
        $result = $delhivery->createShipments($pickup, $shipments);

        $data = $result['data'] ?? [];
        $pkg = $data['packages'][0] ?? null;

        $freshOrder = Order::find($order->id);

        if ($pkg && ($pkg['status'] ?? '') === 'Success') {
            // $freshOrder->carrier = 'delhivery';
            // $freshOrder->awb = $pkg['waybill'] ?? null;
            // $freshOrder->courier_shipment_id = $pkg['refnum'] ?? null;
            // $freshOrder->status = 'shipping';
            // $freshOrder->save();
            $freshOrder->update([
                'carrier' => 'delhivery',
                'awb' => $pkg['waybill'] ?? null,
                'courier_shipment_id' => $pkg['refnum'] ?? null,
                'status' => 'shipping',          // your order status
                'shipment_status' => 'Success',  // new column
                'shipment_message' => 'Shipment created successfully',
                'shipment_response' => json_encode($pkg)
            ]);
            try {
                $orderstatushistory = new OrderStatusHistory();
                $orderstatushistory->order_id = $order->id;
                $orderstatushistory->status = 'shipping';
                $orderstatushistory->remark = 'Shipment created successfully';
                $orderstatushistory->save();
            } catch (Exception $e) {
                Log::info($e->getMessage());
            }

            Alert::toast("Shipment created successfully", 'success');

            return redirect()->back()->with('success', 'Shipment created successfully');
        } else {
            $remarkMessage = '';

            if (!empty($pkg['remarks'])) {
                // If it's array, take first element
                if (is_array($pkg['remarks'])) {
                    $remarkMessage = $pkg['remarks'][0];
                } else {
                    $remarkMessage = $pkg['remarks'];
                }
            }
            $freshOrder->update([
                'shipment_status' => 'Fail',
                'shipment_message' => $remarkMessage ?? 'Shipment failed',
                'shipment_response' => json_encode($pkg)
            ]);
            Alert::toast("Shipment Creation Failed", 'warning');
            return redirect()->back()->with('warning', 'Shipment Creation Failed');
        }
    }

    public function oldcreateDelhiveryMPS(Request $request, DelhiveryService $delhivery)
    {
        $order = Order::find($request->order_id);

        $address = (object) $order->shipping_address;

        $order_items = OrderItem::where('order_id', $order->id)->get();
        // dd($order_items);
        $weight = 0;
        $free = 0;
        $selling_price = 0;
        foreach ($order_items as $item) {
            $product = Product::where(['id' => $item->variant->product_id])->first();
            $stock = ProductVariant::where(['id' => $item->product_variant_id])->first();
            $weight += $stock->weight * $item->quantity;

            if ($product->shipping_type != 'free') {
                $free += 1;
            }

            $name = $product->title . ' SKU -' . $stock->sku;
            $sku = $stock->sku;
            $units = $item->quantity;
            $selling_price += $item->subtotal;

            if (count($order_items) == 1) {
                $height = $stock->height ?? "8";
                $width = $stock->width ?? "25";
                $length = $stock->length ?? "8";
            } else {
                $height = "8";
                $width = "25";
                $length = "8";
            }
        }



        $pickup = [
            'name' => 'SAMRUDDHI SILK HOUSE',
        ];

        $shipments = [
            [
                'order' => $order->invoice_id,
                'weight' => $weight * 1000,
                'mps_amount' => $order->delivery_total ?? 0,
                'mps_children' => '1',
                'pin' => $address->pincode,
                'products_desc' => $name,
                'sku' => $sku,
                'hsn_code' => $sku,
                'quantity' => $units,
                'add' => $address->address . ',' . $address->address_2 . ',' . $address->landmark,
                "shipping_mode" => "Surface",
                'state' => $address->state,
                "shipment_height" => $height ?? "8",
                "shipment_width" => $width ?? "25",
                "shipment_length" => $length ?? "8",
                'city' => $address->city,
                'waybill' => '',
                'phone' => $address->mobile,
                'payment_mode' => 'Prepaid',
                'name' => $address->name,
                'total_amount' => $order->grand_total ?? $selling_price,
                // 'total_amount'   => $selling_price,
                'country' => 'India',
            ]
        ];
        $result = $delhivery->createShipments($pickup, $shipments);
        $data = $result['data'] ?? [];
        $pkg = $data['packages'][0] ?? null;

        if ($pkg && ($pkg['status'] ?? '') === 'Success') {

            DB::transaction(function () use ($pkg, $order) {

                $freshOrder = Order::find($order->id);

                $freshOrder->carrier = 'delhivery';
                $freshOrder->awb = $pkg['waybill'] ?? null;
                $freshOrder->courier_shipment_id = $pkg['refnum'] ?? null;
                $freshOrder->status = 'shipping';
                $freshOrder->save();
            });

            return redirect()->back()->with('success', 'Shipment created successfully');
        } else {
            $freshOrder = Order::find($order->id);

            $freshOrder->update([
                'shipment_status' => 'Fail',
                'shipment_message' => $pkg['remarks'] ?? 'Shipment failed',
                'shipment_response' => json_encode($pkg)
            ]);
            return redirect()->back()->with('warning', 'Shipment Creation Failed');
        }
    }

    public function print($id)
    {
        $order = Order::with(['items', 'payments', 'user'])->findOrFail($id);
        $site = SiteSetting::first();

        return view('admin.orders.print', compact('order', 'site'));
    }

    public function downloadInvoice($orderId)
    {
        $order = Order::with(['user', 'items', 'payments'])->findOrFail($orderId);
        $site = SiteSetting::first();

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order', 'site'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $order->invoice_id . '.pdf');
    }

    public function export(Request $request)
    {
        $query = Order::where('order_type', 'preorder');
        if ($request->status) {
            $query->where('status', $request->status);
        }
        /* ✅ DATE FILTER */
        if ($request->date) {
            $query->whereDate('bill_date', $request->date);
        }
        $data = $query->latest()->get(); // no pagination

        return Excel::download(
            new OrdersExport($data),
            'orders.xlsx'
        );
    }


    public function todayOrdersExport(Request $request)
    {
        $query = Order::query();

        // ✅ Only today's data
        $query->whereDate('created_at', Carbon::today());

        // Optional filters (if needed later)
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();

        return Excel::download(
            new TodayOrdersExport($data),
            'today_orders.xlsx'
        );
    }

    public function todayTransactionsExport(Request $request)
    {
        $query = Payment::where('status', 'paid');

        // ✅ Only today's data
        $query->whereDate('created_at', Carbon::today());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $data = $query->latest()->get();

        return Excel::download(
            new TodayTransactionsExport($data),
            'today_transactions.xlsx'
        );
    }

    private function sendWhatsAppMessage($cust_mobile, $templateName, array $fields = [])
    {

        $data = [
            "from_phone_number_id" => "1070303949494176",
            "phone_number" => '91' . $cust_mobile,
            "template_name" => $templateName,
            "template_language" => "en_Us",
            "header_image" => "https://cdn.pixabay.com/photo/2015/01/07/15/51/woman-591576_1280.jpg",
            "header_video" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document" => "http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4",
            "header_document_name" => "",
            "header_field_1" => "{full_name}",
            "location_latitude" => "",
            "location_longitude" => "",
            "location_name" => "",
            "location_address" => "",
            "field_1" => $fields['field_1'] ?? '',
            "field_2" => $fields['field_2'] ?? '',
            "field_3" => $fields['field_3'] ?? '',
            "field_4" => $fields['field_4'] ?? '',
            "field_5" => $fields['field_5'] ?? '',
            "button_0" => $fields['field_1'],
            "button_1" => "{phone_number}",
            "copy_code" => $fields['field_1'],
        ];

        return $data;
    }
    public function updateAddress(Request $request)
    {
        // $data = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'mobile' => 'required|string|max:15',
        //     'email' => 'nullable|email',
        //     'gst' => 'nullable|string|max:50',
        //     'address' => 'required|string',
        //     'address_2' => 'nullable|string',
        //     'city' => 'required|string|max:100',
        //     'state_id' => 'required|integer',
        //     'state' => 'required|string',
        //     'pincode' => 'required|string|max:10',
        //     'landmark' => 'nullable|string',
        // ]);

        $order = Order::where('id', $request->order_id)->first();

        if (!$order) {
            Alert::toast('Order Not Found', 'warning');
            return redirect()->back();
        }

        DB::beginTransaction();

        try {

            // Update fields
            $addressSnapshot = [
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'gst' => $request->gst,
                'address' => $request->address,
                'address_2' => $request->address_2,
                'city' => $request->city,
                'pincode' => $request->pincode,
                'landmark' => $request->landmark,
                'state_id' => $request->state_id,
            ];
            if (!empty($request->state_id)) {
                $state = State::find($request->state_id);
                $addressSnapshot['state'] = $state->name ?? null;
            }
            $order->shipping_address = $addressSnapshot;
            $order->billing_address = $addressSnapshot;

            $order->save();

            DB::commit();

            Alert::toast('Address Updated Successfully', 'success');
            return redirect()->back();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating address: ' . $e->getMessage());

            Alert::toast('Unable To Update Address', 'warning');
            return redirect()->back();
        }
    }

    public function addNote(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:admin,customer',
            'note' => 'required|string'
        ]);

        OrderNote::create([
            'order_id' => $request->order_id,
            'type' => $request->type,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Note added successfully');
    }


    // ✅ Delete Note (optional but useful)
    public function deleteNote($id)
    {
        $note = OrderNote::findOrFail($id);
        $note->delete();

        return back()->with('success', 'Note deleted');
    }

    public function createBulkParcel(Request $request)
    {
        $orderIds = $request->order_ids;

        if (!$orderIds || count($orderIds) == 0) {
            return response()->json([
                'status' => false,
                'message' => 'No orders selected'
            ]);
        }
        $delaySeconds = 0;
        // Dispatch one job for each order
        foreach ($orderIds as $orderId) {
            CreateParcelJob::dispatch($orderId)->delay(now()->addSeconds($delaySeconds));
            $delaySeconds += 1;
        }

        return response()->json([
            'status' => true,
            'message' => 'Orders added to queue one by one'
        ]);
    }

    public function createBulkDTDCParcel(Request $request)
    {
        $orderIds = $request->order_ids;

        if (!$orderIds || count($orderIds) == 0) {

            return response()->json([
                'status' => false,
                'message' => 'No orders selected'
            ]);
        }
        $delaySeconds = 0;

        foreach ($orderIds as $orderId) {

            CreateDTDCParcelJob::dispatch($orderId)
                ->delay(now()->addSeconds($delaySeconds));

            $delaySeconds += 1;
        }

        return response()->json([
            'status' => true,
            'message' => count($orderIds) . ' DTDC shipments added to queue'
        ]);
    }

    // public function createBulkParcel(Request $request, DelhiveryService $delhivery)
    // {
    //     $orderIds = $request->order_ids;
    //     // dd($orderIds);
    //     if (!$orderIds || count($orderIds) === 0) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No orders selected'
    //         ]);
    //     }

    //     $orders = Order::whereIn('id', $orderIds)->get();

    //     $pickup = [
    //         'name' => 'SAMRUDDHI SILK HOUSE',
    //     ];

    //     $shipments = [];

    //     foreach ($orders as $order) {

    //         // ✅ Skip invalid orders
    //         if ($order->payment_status !== 'paid' || $order->awb) {
    //             continue;
    //         }

    //         $address = (object) $order->shipping_address;

    //         $order_items = OrderItem::with('variant.product')
    //             ->where('order_id', $order->id)
    //             ->get();

    //         if ($order_items->isEmpty()) {
    //             continue;
    //         }

    //         $totalWeight = 0;
    //         $totalAmount = 0;
    //         $totalQty = 0;

    //         $height = 0;
    //         $width  = 0;
    //         $length = 0;

    //         $orderItemsPayload = [];

    //         foreach ($order_items as $item) {

    //             $product = $item->variant->product;
    //             $stock   = $item->variant;

    //             $itemWeight = ($stock->weight ?? 0) * $item->quantity;
    //             $totalWeight += $itemWeight;

    //             $totalAmount += $item->subtotal;
    //             $totalQty    += $item->quantity;

    //             $height = max($height, $stock->height ?? 8);
    //             $width  = max($width, $stock->width ?? 25);
    //             $length = max($length, $stock->length ?? 8);

    //             $orderItemsPayload[] = [
    //                 "name"          => $product->title,
    //                 "sku"           => $stock->sku,
    //                 "units"         => (int) $item->quantity,
    //                 "selling_price" => (float) $item->unit_price,
    //             ];
    //         }

    //         $shipments[] = [
    //             'order'            => $order->invoice_id ?? $order->id,
    //             'weight'           => $totalWeight * 1000,
    //             'pin'              => $address->pincode,
    //             'add'              => $address->address . ',' . $address->address_2 . ',' . $address->landmark,
    //             'city'             => $address->city,
    //             'state'            => $address->state,
    //             'phone'            => $address->mobile,
    //             'name'             => $address->name,
    //             'payment_mode'     => 'Prepaid',
    //             'total_amount'     => $order->grand_total ?? $totalAmount,
    //             'country'          => 'India',

    //             "shipment_height"  => $height ?: 8,
    //             "shipment_width"   => $width ?: 25,
    //             "shipment_length"  => $length ?: 8,
    //             "shipping_mode"    => "Surface",

    //             'quantity'         => $totalQty,

    //             "order_items"      => $orderItemsPayload,

    //             "products_desc" => implode("\n", array_map(function ($item) {
    //                 return $item['name'] . " (" . $item['sku'] . ") - " .
    //                     $item['units'] . " x " . $item['selling_price'];
    //             }, $orderItemsPayload)),
    //         ];
    //     }

    //     if (empty($shipments)) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No valid orders for shipment'
    //         ]);
    //     }

    //     // ✅ API CALL (Bulk)
    //     $result = $delhivery->createShipments($pickup, $shipments);

    //     $data = $result['data'] ?? [];
    //     $packages = $data['packages'] ?? [];

    //     $successCount = 0;
    //     $failedOrders = [];

    //     // DB::transaction(function () use ($packages) {

    //     foreach ($packages as $pkg) {
    //         DB::beginTransaction();
    //         try {
    //             $ref = $pkg['refnum'] ?? null;

    //             $order = Order::where('invoice_id', $ref)
    //                 ->orWhere('id', $ref)
    //                 ->first();

    //             if (!$order) continue;

    //             if (($pkg['status'] ?? '') === 'Success') {

    //                 $order->update([
    //                     'carrier' => 'delhivery',
    //                     'awb' => $pkg['waybill'] ?? null,
    //                     'courier_shipment_id' => $pkg['refnum'] ?? null,
    //                     'status' => 'shipping',

    //                     // ✅ New fields
    //                     'shipment_status' => 'Success',
    //                     'shipment_message' => 'Shipment created successfully',
    //                     'shipment_response' => json_encode($pkg)
    //                 ]);
    //             } else {
    //                 $errorMsg = '';

    //                 if (!empty($pkg['remarks'])) {
    //                     $errorMsg = is_array($pkg['remarks']) ? implode(', ', $pkg['remarks']) : $pkg['remarks'];
    //                 }
    //                 $order->update([

    //                     // ❌ Failed shipment
    //                     'shipment_status' => 'Fail',
    //                     'shipment_message' => $errorMsg ?? 'Shipment failed',
    //                     'shipment_response' => json_encode($pkg)
    //                 ]);
    //             }

    //             DB::commit();
    //         } catch (\Exception $e) {
    //             DB::rollBack();
    //         }
    //     }
    //     // });
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Bulk shipment created successfully',
    //         'data' => $result
    //     ]);
    // }


    public function refreshAwbStatus(Request $request)
    {
        $orderIds = $request->order_ids;
        if (!$orderIds || count($orderIds) === 0) {
            return response()->json([
                'status' => false,
                'message' => 'No orders selected'
            ]);
        }

        $orders = Order::whereIn('id', $orderIds)
            ->whereNotNull('awb')
            ->get();
        foreach ($orders as $order) {
            try {
                $dlv = app(DelhiveryService::class);
                $resp = $dlv->track($order->awb);
                $tracking = $this->normalizeDelhiveryTracking($resp);

                $status = strtolower($tracking['status'] ?? '');
                // Optional mapping
                if ($status === 'delivered') {
                    $order->status = 'delivered';
                } elseif (in_array($status, ['in transit', 'shipped'])) {
                    $order->status = 'shipped';
                } elseif ($status === 'returned') {
                    $order->status = 'returned';
                }

                $order->shipment_status = !empty($tracking['status']) ? 'Success' : 'Failed';
                $order->shipment_message = $tracking['instructions'] ?? ($tracking['status'] ?? 'No update');
                $order->shipment_response = json_encode($resp);

                $order->save();
            } catch (Exception $e) {
                Log::error("AWB update failed for Order {$order->id}: " . $e->getMessage());
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'AWB statuses refreshed successfully'
        ]);
        // return redirect(route('admin.orders.index', ['payment_status' => 'paid']))
        //     ->with('success', 'AWB statuses refreshed successfully!');
    }
    // public function refreshAwbStatus(Request $request)
    // {
    //     $query = Order::whereNotNull('awb');

    //     // Apply same filters as index page
    //     if ($request->search) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('invoice_no', 'like', "%{$request->search}%")
    //                 ->orWhere('customer_name', 'like', "%{$request->search}%")
    //                 ->orWhere('id', $request->search);
    //         });
    //     }

    //     if ($request->from) {
    //         $query->whereDate('created_at', '>=', $request->from);
    //     }

    //     if ($request->to) {
    //         $query->whereDate('created_at', '<=', $request->to);
    //     }

    //     if ($request->payment_status) {
    //         $query->where('payment_status', $request->payment_status);
    //     }
    //     $query->whereNotIn('status', ['completed']);
    //     // 🔥 Only orders with AWB
    //     $orders = $query->whereNotNull('awb')->get();

    //     $dlv = app(DelhiveryService::class);

    //     // foreach ($orders as $order) {
    //     //     try {
    //     //         $dlv = app(DelhiveryService::class);
    //     //         $resp = $dlv->track($order->awb);
    //     //         $tracking = $this->normalizeDelhiveryTracking($resp);

    //     //         $status = strtolower($tracking['status'] ?? '');
    //     //         // Optional mapping
    //     //         if ($status === 'delivered') {
    //     //             $order->status = 'delivered';
    //     //         } elseif (in_array($status, ['in transit', 'shipped'])) {
    //     //             $order->status = 'shipped';
    //     //         } elseif ($status === 'returned') {
    //     //             $order->status = 'returned';
    //     //         }

    //     //         $order->shipment_status = !empty($tracking['status']) ? 'Success' : 'Failed';
    //     //         $order->shipment_message = $tracking['instructions'] ?? ($tracking['status'] ?? 'No update');
    //     //         $order->shipment_response = json_encode($resp);

    //     //         $order->save();
    //     //     } catch (Exception $e) {
    //     //         Log::error("AWB update failed for Order {$order->id}: " . $e->getMessage());
    //     //     }
    //     // }


    //     $batchSize = 20; // 10-20 is usually safe, adjust per Delhivery limits
    //     $ordersChunks = $orders->chunk($batchSize);

    //     foreach ($ordersChunks as $chunk) {
    //         foreach ($chunk as $order) {
    //             try {
    //                 $resp = $dlv->track($order->awb);
    //                 $tracking = $this->normalizeDelhiveryTracking($resp);

    //                 $status = strtolower($tracking['status'] ?? '');
    //                 if ($status === 'delivered') $order->status = 'delivered';
    //                 elseif (in_array($status, ['in transit', 'shipped'])) $order->status = 'shipped';
    //                 elseif ($status === 'returned') $order->status = 'returned';

    //                 $order->shipment_status = !empty($tracking['status']) ? 'Success' : 'Failed';
    //                 $order->shipment_message = $tracking['instructions'] ?? ($tracking['status'] ?? 'No update');
    //                 $order->shipment_response = json_encode($resp);
    //                 $order->save();
    //             } catch (Exception $e) {
    //                 Log::error("AWB update failed for Order {$order->id}: " . $e->getMessage());
    //             }
    //         }

    //         sleep(1); // 1 second delay between batches
    //     }
    //     return redirect(route('admin.orders.index', ['payment_status' => 'paid']))->with('success', 'AWB statuses refreshed successfully!');
    // }


    protected function normalizeDelhiveryTracking(array $resp): array
    {
        $shipments = Arr::get($resp, 'ShipmentData', []);
        $shipment = is_array($shipments) && count($shipments)
            ? Arr::get($shipments, '0.Shipment', [])
            : [];

        // Primary status block
        $statusTitle = trim((string) Arr::get($shipment, 'Status.Status', ''));
        $statusTime = Arr::get($shipment, 'Status.StatusDateTime');
        $statusLoc = (string) (Arr::get($shipment, 'Status.StatusLocation', '')
            ?: Arr::get($shipment, 'Origin', ''));
        $instructions = (string) Arr::get($shipment, 'Status.Instructions', '');

        // Build events from Scans[]
        $scans = Arr::get($shipment, 'Scans', []);
        $events = [];
        foreach ($scans as $wrap) {
            $s = Arr::get($wrap, 'ScanDetail', []);
            $scanTime = Arr::get($s, 'ScanDateTime', Arr::get($s, 'StatusDateTime'));
            $scanLoc = (string) (Arr::get($s, 'ScannedLocation', Arr::get($s, 'ScanLocation', '')));

            $events[] = [
                'status' => (string) Arr::get($s, 'Scan', ''),
                'description' => (string) (Arr::get($s, 'Instructions', '') ?: Arr::get($s, 'Instructions_r', '')),
                'time' => $this->fmtTime($scanTime),
                'location' => trim($scanLoc),
            ];
        }

        // Add synthesized “current status” event
        if ($statusTitle !== '') {
            $events[] = [
                'status' => $statusTitle,
                'description' => $instructions,
                'time' => $this->fmtTime($statusTime),
                'location' => trim($statusLoc),
            ];
        }

        // Sort latest → oldest
        usort($events, fn($a, $b) => strcmp($b['time'] ?? '', $a['time'] ?? ''));

        // Compute last_update
        $lastUpdate = $this->fmtTime($statusTime) ?: ($events[0]['time'] ?? null);

        // Optional extras
        $destination = (string) (Arr::get($shipment, 'Destination', Arr::get($shipment, 'Consignee.City', '')));
        $orderType = (string) Arr::get($shipment, 'OrderType', '');
        $invoiceAmt = Arr::get($shipment, 'InvoiceAmount');

        // Delivery-related dates
        $expectedDelivery = $this->fmtTime(Arr::get($shipment, 'ExpectedDeliveryDate'));
        $actualDelivery = $this->fmtTime(Arr::get($shipment, 'DeliveryDate'));

        return [
            'status' => $this->normalizeDelhiveryStatus($statusTitle, $events),
            'last_update' => $lastUpdate,
            'destination' => $destination ?: null,
            'order_type' => $orderType ?: null,
            'invoice_amount' => $invoiceAmt,
            'expected_delivery' => $expectedDelivery,
            'delivery_date' => $actualDelivery, // ✅ Delivered date if available
            'events' => $events,
            'raw' => $resp, // remove if not needed
        ];
    }

    protected function normalizeDelhiveryStatus(string $title, array $events): string
    {
        $t = mb_strtolower($title);

        // Common explicit states
        if ($t !== '') {
            if (str_contains($t, 'delivered'))
                return 'delivered';
            if (str_contains($t, 'out for delivery'))
                return 'out_for_delivery';
            if (str_contains($t, 'rto') || str_contains($t, 'return'))
                return 'rto';
            if (str_contains($t, 'cancel'))
                return 'cancelled';
            if (str_contains($t, 'manifest'))
                return 'in_transit';   // e.g., "Manifested"
            if (str_contains($t, 'picked'))
                return 'in_transit';
            if (str_contains($t, 'dispatched') || str_contains($t, 'received at') || str_contains($t, 'transit'))
                return 'in_transit';
        }

        // Fallback to latest event text
        $latest = mb_strtolower((string) ($events[0]['status'] ?? ''));
        if ($latest !== '') {
            if (str_contains($latest, 'delivered'))
                return 'delivered';
            if (str_contains($latest, 'out for delivery'))
                return 'out_for_delivery';
            if (str_contains($latest, 'rto') || str_contains($latest, 'return'))
                return 'rto';
            if (str_contains($latest, 'cancel'))
                return 'cancelled';
            if (str_contains($latest, 'manifest') || str_contains($latest, 'picked') || str_contains($latest, 'dispatched') || str_contains($latest, 'received at') || str_contains($latest, 'transit'))
                return 'in_transit';
        }

        // Nothing concrete → created (label made but no movement)
        return $title ? 'in_transit' : 'created';
    }

    protected function fmtTime($raw): ?string
    {
        if (!$raw)
            return null;
        try {
            return Carbon::parse($raw)->toIso8601String();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function importAwbCsv(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $updated = 0;
        $notFound = 0;

        while (($row = fgetcsv($file)) !== false) {

            $data = array_combine($header, $row);

            $awb = trim($data['Waybill'] ?? '');
            $ref = trim($data['Reference No.'] ?? '');

            if (!$awb || !$ref) {
                continue;
            }

            $order = Order::where('invoice_id', $ref)->whereNull('awb')
                ->first();

            if ($order) {

                $order->update([
                    'carrier' => 'delhivery',
                    'awb' => $awb,
                    'courier_shipment_id' => $ref,
                    'status' => 'shipped',
                    'shipment_status' => 'Success'
                ]);

                $updated++;
            } else {
                $notFound++;
            }
        }

        fclose($file);

        return back()->with('success', "$updated updated, $notFound not found.");
    }



    //DTDC

    public function createDTDCShipment(Request $request, DTDCService $dtdc)
    {
        $order = Order::find($request->order_id);

        if (!$order) {

            return redirect()->back()->with('warning', 'Order not found');
        }
        if ($order) {

            return redirect()->back()->with('warning', 'Order not found');
        }

        $address = (object) $order->shipping_address;

        $orderItems = OrderItem::where('order_id', $order->id)->get();

        $weight = 0;

        $sellingPrice = 0;

        $height = 8;

        $width = 25;

        $length = 8;

        $description = '';

        foreach ($orderItems as $item) {

            $product = Product::find($item->variant->product_id);

            $stock = ProductVariant::find($item->product_variant_id);

            if (!$stock) {
                continue;
            }

            $weight += ($stock->weight ?? 0) * $item->quantity;

            $sellingPrice += $item->subtotal;

            // $description .= $product->title . ' SKU-' . $stock->sku . ', ';
            $description .= $product->title . ' (' . $stock->sku . ')-' . $item->quantity . ', ';
            /*
            |--------------------------------------------------------------------------
            | Single Product Dimension
            |--------------------------------------------------------------------------
            */

            if (count($orderItems) == 1) {

                $height = $stock->height ?? 8;

                $width = $stock->width ?? 25;

                $length = $stock->length ?? 8;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Shipment Payload
        |--------------------------------------------------------------------------
        */

        $shipmentData = [

            'reference_number' => $order->invoice_id,

            'description' => $description,

            'service_type_id' => 'STD EXP-A',

            'load_type' => 'NON-DOCUMENT',

            'length' => $length,

            'width' => $width,

            'height' => $height,

            'weight' => $weight,

            'declared_value' => $order->grand_total ?? $sellingPrice,

            /*
            |--------------------------------------------------------------------------
            | Sender Details
            |--------------------------------------------------------------------------
            */

            'sender_name' => 'BO13369 - SAMRUDDI SILK HOUSE',

            'sender_phone' => '9353264267',

            'sender_address' => '1st left cross, Vokkodi Main Rd, near kallaveshwara medicals, Heggere, Tumakuru, Karnataka 572107',

            'sender_pincode' => '572107',

            'sender_city' => 'TUMKUR',

            'sender_state' => 'KARNATAKA',

            /*
            |--------------------------------------------------------------------------
            | Receiver Details
            |--------------------------------------------------------------------------
            */

            'receiver_name' => $address->name,

            'receiver_phone' => $address->mobile,

            'receiver_address' =>
                $address->address . ',' .
                $address->address_2 . ',' .
                $address->landmark,

            'receiver_pincode' => $address->pincode,

            'receiver_city' => $address->city,

            'receiver_state' => $address->state,

        ];

        /*
        |--------------------------------------------------------------------------
        | Create Shipment
        |--------------------------------------------------------------------------
        */

        $result = $dtdc->createShipment($shipmentData);

        /*
        |--------------------------------------------------------------------------
        | Response Data
        |--------------------------------------------------------------------------
        */

        $response = $result['response'] ?? [];

        $shipment = $response['data'][0] ?? [];

        if (
            $result['success'] === true &&
            isset($shipment['success']) &&
            $shipment['success'] === true
        ) {

            DB::transaction(function () use ($shipment, $response, $order) {

                $freshOrder = Order::find($order->id);

                $freshOrder->carrier = 'dtdc';

                // DTDC Reference Number / AWB
                $freshOrder->awb = $shipment['reference_number'] ?? null;

                $freshOrder->courier_shipment_id =
                    $shipment['reference_number'] ?? null;

                $freshOrder->shipment_status = 'Success';

                $freshOrder->shipment_message = 'Shipment Created Successfully';

                $freshOrder->shipment_response = json_encode($response);

                $freshOrder->status = 'shipping';

                $freshOrder->save();
            });

            return redirect()->back()
                ->with('success', 'DTDC Shipment Created Successfully');
        }

        $message =
            $shipment['message']
            ?? $response['message']
            ?? 'Shipment Creation Failed';

        $order->update([

            'shipment_status' => 'Failed',

            'shipment_message' => $message,

            'shipment_response' => json_encode($response)

        ]);

        return redirect()->back()
            ->with('warning', $message);

    }

    public function cancelDTDCShipment($orderId, DTDCService $dtdc)
    {
        try {

            $order = Order::find($orderId);

            if (!$order) {

                return redirect()->back()
                    ->with('warning', 'Order not found');
            }

            if (empty($order->awb)) {

                return redirect()->back()
                    ->with('warning', 'AWB number not found');
            }
            if ($order->status == 'cancelled') {

                return redirect()->back()
                    ->with('warning', 'Order Already Cancelled');
            }

            $result = $dtdc->cancelShipment($order->awb);

            Log::info('DTDC Cancel Shipment Response', [
                'order_id' => $order->id,
                'awb' => $order->awb,
                'response' => $result
            ]);

            if (
                isset($result['response']['success']) &&
                $result['response']['success'] == true
            ) {

                $order->update([

                    'shipment_status' => 'Cancelled',

                    'shipment_message' => 'Shipment Cancelled Successfully',

                    'shipment_response' => json_encode($result['response']),

                    'status' => 'cancelled'

                ]);

                return redirect()->back()
                    ->with('success', 'Shipment Cancelled Successfully');
            }

            $message =
                $result['response']['message']
                ?? $result['response']['error']
                ?? 'Cancellation Failed';

            $order->update([

                'shipment_status' => 'Failed',

                'shipment_message' => $message,

                'shipment_response' => json_encode($result['response'] ?? [])

            ]);

            return redirect()->back()
                ->with('warning', $message);

        } catch (Exception $e) {

            Log::error('DTDC Cancel Shipment Error', [

                'order_id' => $orderId,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile()

            ]);

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    public function trackDTDCShipment($orderId, DTDCService $dtdc)
    {
        try {

            $order = Order::find($orderId);

            if (!$order) {
                return redirect()->back()
                    ->with('warning', 'Order not found');
            }

            if (empty($order->awb)) {
                return redirect()->back()
                    ->with('warning', 'AWB number not found');
            }

            $result = $dtdc->trackShipment($order->awb);

            Log::info('DTDC Tracking Response', [
                'order_id' => $order->id,
                'awb' => $order->awb,
                'response' => $result
            ]);

            if (!$result['success']) {
                return redirect()->back()->with(
                    'warning',
                    $result['message'] ?? 'Tracking Failed'
                );
            }

            $response = $result['response'];

            $header = $response['trackHeader'] ?? [];

            $currentStatus = $header['strStatus'] ?? '';

            $statusMap = [
                'Pickup Awaited' => 'pending',
                'Pickup Scheduled' => 'pending',
                'Pickup Completed' => 'shipping',
                'Held Up' => 'shipping',
                'In Transit' => 'shipping',
                'Out For Delivery' => 'out_for_delivery',
                'Delivered' => 'delivered',
                'Undelivered' => 'undelivered',
                'RTO' => 'returned',
                'Cancelled' => 'cancelled',
                'Return as per client instruction.' => 'cancelled',
            ];
            $order->update([
                'tracking_response' => json_encode($response),
                'shipment_status' => $currentStatus,
                'status' => $statusMap[$currentStatus] ?? $order->status,
            ]);

            return view('admin.orders.tracking', [
                'order' => $order,
                'response' => $response
            ]);

        } catch (Exception $e) {

            Log::error('DTDC Tracking Error', [
                'order_id' => $orderId,
                'message' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    // public function trackDTDCShipment($orderId, DTDCService $dtdc)
    // {
    //     try {

    //         $order = Order::find($orderId);

    //         if (!$order) {

    //             return redirect()->back()
    //                 ->with('warning', 'Order not found');
    //         }

    //         if (empty($order->awb)) {

    //             return redirect()->back()
    //                 ->with('warning', 'AWB number not found');
    //         }

    //         $result = $dtdc->trackShipment($order->awb);

    //         Log::info('DTDC Tracking Response', [
    //             'order_id' => $order->id,
    //             'awb' => $order->awb,
    //             'response' => $result
    //         ]);

    //         if ($result['success']) {

    //             $response = $result['response'];
    //             $trackingData = $response['response'] ?? [];
    //             $header = $trackingData['trackHeader'] ?? [];

    //             $currentStatus = $header['strStatus'] ?? '';

    //             $order->update([
    //                 'tracking_response' => json_encode($response),
    //                 // 'shipment_status' => $currentStatus,
    //             ]);

    //             return view('admin.orders.tracking', compact(
    //                 'order',
    //                 'response'
    //             ));
    //         }

    //         return redirect()->back()->with(
    //             'warning',
    //             $result['message'] ?? 'Tracking Failed'
    //         );

    //     } catch (Exception $e) {

    //         Log::error('DTDC Tracking Error', [

    //             'order_id' => $orderId,

    //             'message' => $e->getMessage()

    //         ]);

    //         return redirect()->back()
    //             ->with('error', $e->getMessage());
    //     }
    // }

    public function downloadDTDCLabel($orderId, DTDCService $dtdc)
    {
        try {

            $order = Order::find($orderId);

            if (!$order) {

                return redirect()->back()
                    ->with('warning', 'Order not found');
            }

            if (empty($order->awb)) {

                return redirect()->back()
                    ->with('warning', 'AWB number not found');
            }

            $result = $dtdc->generateLabel(
                $order->awb,
                'SHIP_LABEL_4X6'
            );

            if (!$result['success']) {

                return redirect()->back()
                    ->with(
                        'warning',
                        $result['message'] ?? 'Label generation failed'
                    );
            }

            return response(
                $result['response'],
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' =>
                        'inline; filename="DTDC-' .
                        $order->awb .
                        '.pdf"'
                ]
            );

        } catch (Exception $e) {

            \Log::error('DTDC Label Error', [

                'order_id' => $orderId,

                'message' => $e->getMessage()

            ]);

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
