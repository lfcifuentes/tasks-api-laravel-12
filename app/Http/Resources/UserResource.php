<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tasks' => TaskResource::collection($this->whenLoaded('createdTasks')),
            'assigned_tasks' => TaskResource::collection($this->whenLoaded('assignedTasks')),
            'time_logs' => TaskTimeLogResource::collection($this->whenLoaded('timeLogs')),
            'files' => TaskFileResource::collection($this->whenLoaded('files')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
        ];
    }
}
