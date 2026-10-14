<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\FlashSaleProduct;

class CartCollection extends ResourceCollection
{
    public function toArray($request)
    {
        $items = $this->collection->map(function ($item) {
            $v = $item->variant;
            $product = $v->product;

            // snapshot price stored when added/updated in cart
            $unitPrice = $item->unit_price ?? (float) ($v->price ?? 0);
            $actualPrice = (float) ($v->actual_price ?? 0);
            $subtotal = $unitPrice * (int) $item->quantity;
            $actualtotal = $actualPrice * (int) $item->quantity;

            // detect active flash sale for this variant
            $flash = $this->getFlashSaleData($v);

            return [
                'id' => $item->id,
                'quantity' => (int) $item->quantity,

                // prices used for checkout (snapshot)
                'unit_price' => (float) $unitPrice,
                'subtotal' => (float) $subtotal,
                'actualtotal' => (float) $actualtotal,

                // optional: show what the regular price is right now
                'regular_price' => isset($v->price) ? (float) $v->price : null,
                'actual_price' => isset($v->actual_price) ? (float) $v->actual_price : null,

                // flash sale info for UI (does NOT override unit_price)
                'flash_sale' => $flash,
                'product_max_order' => $v->product_max_order,
                'product_min_order' => $v->product_min_order,
                'type' => $item->type,

                'variant' => [
                    'id' => $v->id,
                    'sku' => $v->sku,
                    'slug' => $v->slug,
                    'actual_price' => isset($v->actual_price) ? (float) $v->actual_price : null,
                    'price' => isset($v->price) ? (float) $v->price : null,
                    'discount_percent' => (
                        isset($v->actual_price, $v->price) && $v->actual_price > 0 && $v->price < $v->actual_price
                    ) ? round((($v->actual_price - $v->price) / $v->actual_price) * 100, 2) : 0.0,
                    'image' => $v->image ? asset($v->image) : null,
                    'stock' => (int) $v->stock,
                    'preorder_stock' => (int) $v->preorder_stock,
                    'attrs' => $v->attributeValues->map(fn($val) => [
                        'attribute' => $val->attribute->name,
                        'value' => $val->value ?? $val->name,
                    ])->values(),
                ],

                'product' => [
                    'id' => $product?->id,
                    'title' => $product?->title,
                    'slug' => $product?->slug,
                    'category' => $product?->category?->name,
                ],

                'added_at' => optional($item->created_at)?->toDateTimeString(),
            ];
        })->values();

        $totals = [
            'items' => (int) $items->where('type', 'order')->sum('quantity'),
            'subtotal' => (float) $items->where('type', 'order')->sum('subtotal'),
            'actualtotal' => (float) $items->where('type', 'order')->sum('actualtotal'),
            // extend with discounts/taxes/shipping if needed
            'total' => (float) $items->where('type', 'order')->sum('subtotal'),
        ];
        $preordertotals = [
            'items' => (int) $items->where('type', 'preorder')->sum('quantity'),
            'subtotal' => (float) $items->where('type', 'preorder')->sum('subtotal'),
            'actualtotal' => (float) $items->where('type', 'preorder')->sum('actualtotal'),
            // extend with discounts/taxes/shipping if needed
            'total' => (float) $items->where('type', 'preorder')->sum('subtotal'),
        ];

        return [
            'data' => $items->where('type', 'order')->values(),
            'preorder' => $items->where('type', 'preorder')->values(), // 👈 FIX HERE
            'totals' => $totals,
            'preorder_totals' => $preordertotals,
        ];
    }

    /**
     * Return flash sale data for a given variant (or null if not active)
     */
    private function getFlashSaleData($variant): ?array
    {
        if (!$variant)
            return null;

        $fsp = FlashSaleProduct::where('product_variant_id', $variant->id)
            ->whereHas('flashSale', fn($q) => $q->active())   // expects a local scope `active()` on FlashSale
            ->with('flashSale')
            ->first();

        if (!$fsp)
            return null;

        // expects a helper method on FlashSaleProduct that calculates the effective price
        $flashPrice = $fsp->getEffectivePrice();

        // percent vs current regular price (v->price). Adjust if you prefer actual_price base.
        $base = (float) ($variant->price ?? 0);
        $flashPercent = ($base > 0 && $flashPrice < $base)
            ? round((($base - $flashPrice) / $base) * 100, 2)
            : 0.0;

        return [
            'flash_price' => (float) $flashPrice,
            'flash_discount_percent' => $flashPercent,
            'discount_type' => $fsp->discount_type,
            'discount_value' => (float) $fsp->discount_value,
            'starts_at' => optional($fsp->flashSale->starts_at)?->toDateTimeString(),
            'ends_at' => optional($fsp->flashSale->ends_at)?->toDateTimeString(),
            'title' => $fsp->flashSale?->title,
            'flash_sale_id' => $fsp->flash_sale_id,
        ];
    }
}
