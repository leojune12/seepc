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
            'user_id' => $this->user_id,
            'user' => $this->user,
            'comment' => $this->comment,
            'replies_count' => count($this->replies),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
