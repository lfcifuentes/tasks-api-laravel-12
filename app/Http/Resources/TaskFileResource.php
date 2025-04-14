<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskFileResource extends JsonResource
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
            //'file_path' => $this->file_path,
            'file_url' => $this->when($this->file_url, $this->file_url),
            'user_id' => $this->user_id,
            'task_id' => $this->task_id,
            'user' => new UserResource($this->whenLoaded('user')),
            'task' => new TaskResource($this->whenLoaded('task')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
