<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'code', 'name', 'is_online', 'status', 'image', 'description',
        'config', 'fee_percent', 'fee_fixed', 'sort_order',
    ];
    protected $casts = [
        'config' => 'array',
        'fee_percent' => 'float',
        'fee_fixed'   => 'float',
    ];

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'show';
    }

    public function isOnline(): bool
    {
        return $this->is_online === 'yes';
    }

    // ✅ Use this instead of getKey()
    public function getConfigValue(string $key): ?string
    {
        return $this->config[$key] ?? null;
    }

    public function getPublicConfig(): array
    {
        return [
            'code'        => $this->code,
            'name'        => $this->name,
            'image'       => $this->image ? asset($this->image) : null,
            'description' => $this->description,
            'is_online'   => $this->isOnline(),
            'fee_percent' => $this->fee_percent,
            'fee_fixed'   => $this->fee_fixed,
            'sort_order'  => $this->sort_order,
        ];
    }
}
