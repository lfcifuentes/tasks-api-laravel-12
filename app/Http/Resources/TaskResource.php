<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'completed' => $this->completed,
            'due_date' => $this->due_date,
            'created_by' => $this->created_by,
            'created_user' =>  new UserResource($this->whenLoaded('createdBy')),
            'assigned_to' => $this->assigned_to,
            'assigned_user' => new UserResource($this->whenLoaded('assignedTo')),
            'total_time_spent' => $this->total_time_spent,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
