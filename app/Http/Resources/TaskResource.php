<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the Task into a clean JSON structure.
     * $this->resource is the Task model instance.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'status'      => $this->status,
            'due_date'    => $this->due_date?->format('Y-m-d'), // ?-> = only call if not null
            'created_at'  => $this->created_at->toDateTimeString(),
            // Notice: we don't expose user_id or updated_at — full control!
        ];
    }
}