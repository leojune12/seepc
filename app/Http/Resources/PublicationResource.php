<?php

namespace App\Http\Resources;

use App\Models\Publication\Comment;
use App\Models\Publication\Specification;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PublicationResource extends JsonResource
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
            'photo_path' => $this->photo_path,
            'description' => $this->description,
            'specifications' => new SpecificationResource($this->specification),
            'user' => new UserResource($this->user),
            'likes' => count(LikeResource::collection($this->likes)),
            'liked' => LikeResource::collection($this->likes)->contains('user_id', \auth()->id()),
            'comment_count' => $this->comments_count,
            'comment_page' => 1,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
