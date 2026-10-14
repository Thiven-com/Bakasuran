<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppNotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'is_promotional' => (bool)$this->is_promotional,
            'image' => $this->image ? asset($this->image) : null,
            'icon' => $this->icon ? asset($this->icon) : null,
            'is_read' => (bool)$this->is_read,
            'data' => $this->data,
            'created_at' => optional($this->created_at)?->toDateTimeString(),
            'read_at' => optional($this->read_at)?->toDateTimeString(),
        ];
    }
}
