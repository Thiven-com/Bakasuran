<?php

namespace App\Http\Resources;

use App\Models\OrderNote;
use App\Models\OrderStatusHistory;
use App\Services\Order\DelhiveryService;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($order) {
            return [
                'id'              => $order->id,
                'invoice_id'      => $order->invoice_id,
                'status'          => $order->status,
                'payment_status'  => $order->payment_status,
                'payment_method'  => $order->payment_method,
                'subtotal'        => (float) $order->subtotal,
                'tax_total'       => (float) $order->tax_total,
                'discount_total'  => (float) $order->discount_total,
                'delivery_total'  => (float) $order->delivery_total,
                'shipping_total'  => (float) $order->delivery_total,
                'grand_total'     => (float) $order->grand_total,
                'shipping_address' => $order->shipping_address,
                'billing_address' => $order->billing_address,
                'customer_note'   => $order->customer_note,
                'order_note'      => $order->order_note,
                'order_type' => $order->order_type,
                'tracking_link'   => $order->tracking_link,
                'created_at'      => $order->created_at?->toDateTimeString(),

                // payments
                'payments' => $order->payments->map(function ($p) {
                    return [
                        'id'        => $p->id,
                        'amount'    => (float) $p->amount,
                        'currency'  => $p->currency,
                        'status'    => $p->status,
                        'method'    => $p->method,
                        'provider'  => $p->provider,
                        'provider_order_id'  => $p->provider_order_id,
                        'reference' => $p->reference_no,
                        'paid_at'   => $p->paid_at?->toDateTimeString(),
                    ];
                })->values(),

                'tracking' => $this->buildTrackingForOrder($order),
                'order_history' => OrderStatusHistory::where('order_id', $order->id)->get(),
                


                // full items
                'items' => $order->items->map(function ($item) {
                    return [
                        'id'        => $item->id,
                        'variant_id' => $item->product_variant_id,
                        'title'     => $item->product_title,
                        'sku'       => $item->sku,
                        'quantity'  => (int) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'subtotal'  => (float) $item->subtotal,
                        'seller_id' => $item->seller_id,
                        'image' => $item->variant && $item->variant->image ? asset($item->variant->image) : null,
                    ];
                })->values(),
                'ordernotes' => $this->orderNotes($order->id),
                'invoice_pdf' => !in_array($order->status, ['cancelled', 'pending']) 
                 ? route('orderInvoice', $order->id) 
                 : null,
                // shipments
                // 'shipments' => $order->shipments->map(function ($s) {
                //     return [
                //         'id'             => $s->id,
                //         'seller_id'      => $s->seller_id,
                //         'shipment_id'    => $s->shipment_id,
                //         'carrier'        => $s->carrier,
                //         'tracking_no'    => $s->tracking_no,
                //         'delivery_charge'=> (float) $s->delivery_charge,
                //         'status'         => $s->status,
                //         'shipping_address' => $s->shipping_address,
                //         'items'          => $s->items->map(fn($si) => $si->order_item_id)->values(),
                //     ];
                // })->values(),
            ];
        });
    }

    protected function emptyTracking(string $status = 'unavailable'): array
    {
        return [
            'status'      => $status,
            'last_update' => null,
            'events'      => [],
            'raw'         => null,
        ];
    }

    protected function buildTrackingForOrder($order): array
    {
        $awb = trim((string)($order->awb ?? ''));
        if ($awb === '') {
            return $this->emptyTracking('unavailable');
        }

        $carrier = strtolower((string)($order->carrier ?? ''));
        // tolerate common typos
        if (str_starts_with($carrier, 'delh')) $carrier = 'delhivery';
        if (str_starts_with($carrier, 'shipr')) $carrier = 'shiprocket';

        $cacheKey = "trk:{$carrier}:{$awb}";

        try {
            return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($carrier, $awb) {
                switch ($carrier) {
                    case 'delhivery':
                        $dlv = app(DelhiveryService::class);
                        $resp = $dlv->track($awb);
                        return $this->normalizeDelhiveryTracking($resp);

                    case 'shiprocket':
                        // /** @var ShipRocketService $sr */
                        // $sr = app(ShipRocketService::class);
                        // $resp = $sr->track($awb);
                        // return $this->normalizeShiprocketTracking($resp);

                    default:
                        return $this->emptyTracking('unsupported');
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Tracking failed', ['carrier' => $carrier, 'awb' => $awb, 'err' => $e->getMessage()]);
            return $this->emptyTracking('unavailable');
        }
    }
    protected function normalizeDelhiveryTracking(array $resp): array
    {
        $shipments = Arr::get($resp, 'ShipmentData', []);
        $shipment  = is_array($shipments) && count($shipments)
            ? Arr::get($shipments, '0.Shipment', [])
            : [];

        // Primary status block
        $statusTitle = trim((string) Arr::get($shipment, 'Status.Status', ''));
        $statusTime  = Arr::get($shipment, 'Status.StatusDateTime');
        $statusLoc   = (string) (Arr::get($shipment, 'Status.StatusLocation', '')
            ?: Arr::get($shipment, 'Origin', ''));
        $instructions = (string) Arr::get($shipment, 'Status.Instructions', '');

        // Build events from Scans[]
        $scans  = Arr::get($shipment, 'Scans', []);
        $events = [];
        foreach ($scans as $wrap) {
            $s = Arr::get($wrap, 'ScanDetail', []);
            $scanTime = Arr::get($s, 'ScanDateTime', Arr::get($s, 'StatusDateTime'));
            $scanLoc  = (string) (Arr::get($s, 'ScannedLocation', Arr::get($s, 'ScanLocation', '')));

            $events[] = [
                'status'      => (string) Arr::get($s, 'Scan', ''),
                'description' => (string) (Arr::get($s, 'Instructions', '') ?: Arr::get($s, 'Instructions_r', '')),
                'time'        => $this->fmtTime($scanTime),
                'location'    => trim($scanLoc),
            ];
        }

        // Add synthesized “current status” event
        if ($statusTitle !== '') {
            $events[] = [
                'status'      => $statusTitle,
                'description' => $instructions,
                'time'        => $this->fmtTime($statusTime),
                'location'    => trim($statusLoc),
            ];
        }

        // Sort latest → oldest
        usort($events, fn($a, $b) => strcmp($b['time'] ?? '', $a['time'] ?? ''));

        // Compute last_update
        $lastUpdate = $this->fmtTime($statusTime) ?: ($events[0]['time'] ?? null);

        // Optional extras
        $destination   = (string) (Arr::get($shipment, 'Destination', Arr::get($shipment, 'Consignee.City', '')));
        $orderType     = (string) Arr::get($shipment, 'OrderType', '');
        $invoiceAmt    = Arr::get($shipment, 'InvoiceAmount');

        // Delivery-related dates
        $expectedDelivery = $this->fmtTime(Arr::get($shipment, 'ExpectedDeliveryDate'));
        $actualDelivery   = $this->fmtTime(Arr::get($shipment, 'DeliveryDate'));

        return [
            'status'             => $this->normalizeDelhiveryStatus($statusTitle, $events),
            'last_update'        => $lastUpdate,
            'destination'        => $destination ?: null,
            'order_type'         => $orderType ?: null,
            'invoice_amount'     => $invoiceAmt,
            'expected_delivery'  => $expectedDelivery,
            'delivery_date'      => $actualDelivery, // ✅ Delivered date if available
            'events'             => $events,
            'raw'                => $resp, // remove if not needed
        ];
    }

    protected function normalizeDelhiveryStatus(string $title, array $events): string
    {
        $t = mb_strtolower($title);

        // Common explicit states
        if ($t !== '') {
            if (str_contains($t, 'delivered')) return 'delivered';
            if (str_contains($t, 'out for delivery')) return 'out_for_delivery';
            if (str_contains($t, 'rto') || str_contains($t, 'return')) return 'rto';
            if (str_contains($t, 'cancel')) return 'cancelled';
            if (str_contains($t, 'manifest')) return 'in_transit';   // e.g., "Manifested"
            if (str_contains($t, 'picked')) return 'in_transit';
            if (str_contains($t, 'dispatched') || str_contains($t, 'received at') || str_contains($t, 'transit'))
                return 'in_transit';
        }

        // Fallback to latest event text
        $latest = mb_strtolower((string)($events[0]['status'] ?? ''));
        if ($latest !== '') {
            if (str_contains($latest, 'delivered')) return 'delivered';
            if (str_contains($latest, 'out for delivery')) return 'out_for_delivery';
            if (str_contains($latest, 'rto') || str_contains($latest, 'return')) return 'rto';
            if (str_contains($latest, 'cancel')) return 'cancelled';
            if (str_contains($latest, 'manifest') || str_contains($latest, 'picked') || str_contains($latest, 'dispatched') || str_contains($latest, 'received at') || str_contains($latest, 'transit'))
                return 'in_transit';
        }

        // Nothing concrete → created (label made but no movement)
        return $title ? 'in_transit' : 'created';
    }

    protected function fmtTime($raw): ?string
    {
        if (!$raw) return null;
        try {
            return Carbon::parse($raw)->toIso8601String();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function orderNotes($orderId)
    {
        $notes = OrderNote::where('order_id', $orderId)->where('type', 'customer')->get();
        return $notes;
    }
}
