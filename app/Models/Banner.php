<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Banner extends Model
{
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'active'    => 'boolean',
    ];

    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            });
    }

    /**
     * Accessor: check if this banner is active at current time
     */
    public function getIsActiveAttribute(): bool
    {
        $now = Carbon::now();
        if (!$this->active) return false;
        if ($this->starts_at && $this->starts_at->gt($now)) return false;
        if ($this->ends_at && $this->ends_at->lt($now)) return false;
        return true;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
