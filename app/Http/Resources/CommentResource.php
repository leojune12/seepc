<?php

namespace App\Http\Resources;

use App\Models\Publication\Comment;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user),
            'comment' => $this->comment,
            'replies' => [],
            'replies_count' => $this->replies_count,
            'publication_id' => $this->commentable_id,
            'created_at' => $this->created_at,
        ];
    }
}
