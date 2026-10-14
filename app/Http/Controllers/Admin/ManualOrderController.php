<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\ProductVariant;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class ManualOrderController extends Controller
{
    //

    public function createOrder(Request $request)
    {
        $this->storeProducts();
        $filePath = public_path('products.json');

        if (!File::exists($filePath)) {
            Alert::toast('Product Details Not Found', 'warning');
            return redirect(route('admin.products.index'));
        }
        $jsonContent = File::get($filePath);
        $data = json_decode($jsonContent, true);

        $customers = Customer::get();
        $products = array_slice($data, 0, 40);
        $carts = session()->get('cart', []);
        // dd($carts);
        $states = State::orderBy('name')->get();

        return view('admin.orders.manual_order', compact('products', 'carts', 'customers', 'states'));
    }

    public function storeProducts()
    {
        $variants = ProductVariant::get();
        $data = [];

        foreach ($variants as $variant) {
            $attr['name'] = $variant->product->title ?? '';
            $attr['image'] = $variant->product->image ?? '';
            $attr['sale_price'] = $variant->price ?? '';
            $attr['actual_price'] = $variant->actual_price ?? '';
            $attr['product_id'] = $variant->product_id ?? '';
            $attr['product_variant_id'] = $variant->id ?? '';
            $attr['stock'] = $variant->stock ?? '';
            $attr['sku'] = $variant->sku ?? '';
            $data[] = $attr;
        }

        // Define the path to save the file
        $filePath = public_path('products.json');

        // Save the JSON data to the file
        File::put($filePath, json_encode($data, JSON_PRETTY_PRINT));
        return true;
    }

    public function clearSession()
    {
        session()->forget('cart');
        return redirect(route('admin.createOrder'));
    }

    public function addToCart(Request $request)
    {
        $action = $request->input('action'); // 'increase', 'decrease', or 'delete'

        // Validate input
        $request->validate([
            'sku' => 'required|string',
        ]);

        $sku = $request->input('sku');

        // Retrieve or initialize the cart session
        $cart = session()->get('cart', []);

        // Find product index in cart
        $existingProductIndex = array_search($sku, array_column($cart, 'sku'));

        if ($action === 'delete') {
            if ($existingProductIndex !== false) {
                unset($cart[$existingProductIndex]);
                $cart = array_values($cart); // Reindex array
                session()->put('cart', $cart);
                return response()->json(['success' => 1, 'cart' => $cart, 'message' => 'Product removed from cart']);
            } else {
                return response()->json(['success' => 0, 'message' => 'Product not found in cart']);
            }
        }

        // If not delete, we need to access product data
        $filePath = public_path('products.json');

        if (!File::exists($filePath)) {
            return response()->json(['success' => 0, 'message' => 'Products file not found']);
        }

        $jsonContent = File::get($filePath);
        $data = json_decode($jsonContent, true);

        // Find product by barcode
        $product = collect($data)->firstWhere('sku', $sku);

        if (!$product) {
            return response()->json(['success' => 0, 'message' => 'Product not found']);
        }

        if ($existingProductIndex !== false) {
            // Update quantity and total if the product already exists in the cart
            if ($action === 'increase') {
                $cart[$existingProductIndex]['quantity'] += 1;
            } elseif ($action === 'decrease' && $cart[$existingProductIndex]['quantity'] > 1) {
                $cart[$existingProductIndex]['quantity'] -= 1;
            }

            $cart[$existingProductIndex]['total'] = $cart[$existingProductIndex]['quantity'] * $cart[$existingProductIndex]['price'];
            $cart[$existingProductIndex]['actual_total'] = $cart[$existingProductIndex]['quantity'] * $product['actual_price'];
        } else {
            // Add new product to the cart
            $cart[] = [
                'name' => $product['name'],
                'image' => $product['image'],
                'price' => $product['sale_price'],
                'sku' => $product['sku'],
                'quantity' => 1,
                'total' => $product['sale_price'],
                'actual_total' => $product['actual_price'],
            ];
        }

        // Save the updated cart data to the session
        session()->put('cart', $cart);

        // Return success response
        return response()->json(['success' => 1, 'cart' => $cart, 'message' => 'Cart updated']);
    }


    public function fetchCart(Request $request)
    {
        $filePath = public_path('products.json');

        if (!File::exists($filePath)) {
            return response()->json(['success' => 0, 'message' => 'Products file not found']);
        }
        // Retrieve or initialize the cart session
        $cart = session()->get('cart', []);
        session()->put('cart', $cart);
        // Return success response
        return response()->json(['success' => 1, 'cart' => $cart, 'message' => 'Cart updated']);
    }

    public function getCustomerAddresses(Request $request)
    {
        $customerId = $request->customer_id;

        // Fetch customer addresses
        $addresses = Address::where('customer_id', $customerId)->get();

        return response()->json($addresses);
    }

    public function addCustomer(Request $request)
    {
        $customer = Customer::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
        ]);

        return response()->json([
            'success' => 1,
            'customer' => $customer
        ]);
    }
    public function addAddress(Request $request)
    {
        $address = Address::create([
            'customer_id' => $request->customer_id,
            'name'        => $request->name,
            'gst'         => $request->gst,
            'address'     => $request->address,
            'address_2'   => $request->address_2,
            'city'        => $request->city,
            'pincode'     => $request->pincode,
            'landmark'    => $request->landmark,
            'email'       => $request->email,
            'mobile'      => $request->mobile,
            'state_id'    => $request->state_id,
            'state'       => $request->state,
        ]);

        return response()->json([
            'success' => 1,
            'address' => [
                'id' => $address->id,
                'label' => $address->name . ' - ' . $address->city . ' (' . $address->pincode . ')'
            ]
        ]);
    }

    public function manualCheckout(Request $request)
    {
        $request->validate([
            'customer_id'        => 'required|exists:customers,id',
            'customer_address'   => 'required|exists:addresses,id',
            'payment_method'     => 'required|string',
        ]);

        $user = Customer::findOrFail($request->customer_id);
        $cart = session()->get('cart');

        if (empty($cart)) {
            Alert::toast('Please Add Items To Checkout', 'warning');
            return back();
        }

        // ✅ Get Address
        $address = Address::where('id', $request->customer_address)
            ->where('customer_id', $user->id)
            ->firstOrFail();

        // ✅ Snapshot
        $addressSnapshot = [
            'name'      => $address->name,
            'mobile'    => $address->mobile,
            'email'     => $address->email,
            'gst'       => $address->gst,
            'address'   => $address->address,
            'address_2' => $address->address_2,
            'city'      => $address->city,
            'pincode'   => $address->pincode,
            'landmark'  => $address->landmark,
            'state_id'  => $address->state_id,
            'state'     => $address->state,
        ];

        DB::beginTransaction();

        try {

            $subtotal = 0;
            $itemsPrepared = [];

            // ✅ Prepare cart using ProductVariant
            foreach ($cart as $item) {

                $variant = ProductVariant::with('product')
                    ->where('sku', $item['sku']) // 🔥 IMPORTANT
                    ->firstOrFail();

                if ($variant->stock < $item['quantity']) {
                    Alert::toast("Stock not available for SKU: " . $variant->sku, 'warning');
                    return back();
                }

                $lineTotal = $variant->price * $item['quantity'];
                $subtotal += $lineTotal;

                $itemsPrepared[] = [
                    'variant'   => $variant,
                    'quantity'  => $item['quantity'],
                    'unit_price' => $variant->price,
                    'subtotal'  => $lineTotal,
                ];
            }

            $discount = $request->discount ?? 0;
            $grandTotal = $subtotal - $discount;

            // ✅ Create Order
            $order = new Order();
            $order->customer_id = $user->id;
            $order->invoice_id = 'Manual-' . now()->format('YmdHis');
            $order->subtotal = $subtotal;
            $order->discount_total = $discount;
            $order->grand_total = $grandTotal;
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'paid';
            $order->status = 'placed';
            $order->shipping_address = $addressSnapshot;
            $order->billing_address = $addressSnapshot;
            $order->order_type = 'manual_order';
            $order->save();
            try{
            $orderstatushistory = new OrderStatusHistory();
            $orderstatushistory->order_id = $order->id;
            $orderstatushistory->status = 'placed';
            $orderstatushistory->remark = 'Order placed via admin manual order';
            $orderstatushistory->save();
            }catch(\Exception $e){
                Log::info($e->getMessage());
            }

            $invoiceId = 'SSHM' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
            $order->invoice_id       = $invoiceId;
            $order->save();

            // ✅ Order Items
            foreach ($itemsPrepared as $row) {

                $variant = $row['variant'];

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_variant_id = $variant->id;
                $orderItem->product_title = $variant->product->title;
                $orderItem->sku = $variant->sku;
                $orderItem->seller_id = 0;
                $orderItem->unit_price = $row['unit_price'];
                $orderItem->quantity = $row['quantity'];
                $orderItem->subtotal = $row['subtotal'];
                $orderItem->save();

                // ✅ Reduce stock
                $variant->stock -= $row['quantity'];
                $variant->save();
            }

            // ✅ Payment
            $payment = new Payment();
            $payment->order_id = $order->id;
            $payment->user_id = $user->id;
            $payment->amount = $grandTotal;
            $payment->status = 'success';
            $payment->method = $request->payment_method;
            $payment->meta = json_encode($request->except('_token'));
            $payment->save();

            DB::commit();

            session()->forget('cart');

            Alert::toast('Order Created Successfully', 'success');

            return redirect()->route('admin.manualorders', ['payment_status' => 'paid']);
        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->route('admin.createOrder')
                ->withInput() // 🔥 keeps old form data
                ->with('error', $e->getMessage());
        }
    }
}
