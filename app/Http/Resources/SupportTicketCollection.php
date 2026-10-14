<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;

class SupportTicketCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->transform(function ($data) {
                return [
                    'id' => $data->id,
                    'user_id' => $data->user_id,
                    'activity_id' => $data->activity_id,
                    'name' => ucwords(strtolower($data->name)),
                    'title' => $data->title,
                    'date' => $data->date,
                    'category' => $data->category,
                    'description' => $data->description,
                    'image' => $data->image,
                    'image_url' => isset($data->image) ? url($data->image) : null,
                    'status' => $data->status,
                    'comment' => $data->comment,
                    'created_at' => @Carbon::parse($data->created_at)->format('Y-m-d H:i:s'),
                    'updated_at' => @Carbon::parse($data->updated_at)->format('Y-m-d H:i:s'),
                    'activity' => $data->activity ?? null

                ];
            })
        ];
    }
}
