<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrdersCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->map(function ($order) {
            return [
                'id'            => $order->id,
                'invoice_id'    => $order->invoice_id,
                'status'        => $order->status,
                'payment_status' => $order->payment_status,
                'order_type' => $order->order_type,
                'grand_total'   => (float) $order->grand_total,
                'shipping_total'   => (float) $order->delivery_total,
                'created_at'    => $order->created_at?->toDateTimeString(),
                // Just 1–2 items preview
                'items_preview' => $order->items->map(function ($item) {
                    $variant = $item->variant;

                    $thumb = $variant?->media
                        ? $variant->media->sortByDesc('is_primary')->sortBy('sort_order')->first()
                        : null;

                    $image = $thumb?->thumbnail_url ?: $thumb?->url ?: $variant?->image;
                    return [
                        'title'     => $item->product_title,
                        'quantity'  => (int) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'image' => $item->variant && $item->variant->image ? asset($item->variant->image) : null,

                    ];
                }),
                'items_count' => $order->items->count(),
            ];
        });
    }
}
