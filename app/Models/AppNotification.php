<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'title',
        'message',
        'type',
        'is_promotional',
        'image',
        'icon',
        'is_read',
        'read_at',
        'data',
    ];

    protected $casts = [
        'is_promotional' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    // relation to user
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // helpers
    public function markAsRead(): bool
    {
        if (! $this->is_read) {
            $this->is_read = true;
            $this->read_at = now();
            return $this->save();
        }
        return false;
    }

    public function markAsUnread(): bool
    {
        $this->is_read = false;
        $this->read_at = null;
        return $this->save();
    }

    // return public representation (paths to full URL)
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'is_promotional' => (bool)$this->is_promotional,
            'image' => $this->image ? asset($this->image) : null,
            'icon'  => $this->icon ? asset($this->icon) : null,
            'is_read' => (bool)$this->is_read,
            'data' => $this->data,
            'created_at' => optional($this->created_at)?->toDateTimeString(),
            'read_at' => optional($this->read_at)?->toDateTimeString(),
        ];
    }
}
