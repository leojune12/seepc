<?php

namespace App\Http\Resources;

use App\Models\Publication\Comment;
use App\Models\Publication\Like;
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
            // 'likes_count' => count(LikeResource::collection($this->likes)),
            'likes_count' => $this->likes_count,
            // 'liked' => LikeResource::collection($this->likes)->contains('user_id', Auth::id()),
            'liked' => Like::where('publication_id', $this->id)->where('user_id', $this->user->id)->first() ? true : false,
            'comments_count' => $this->comments_count,
            'comments' => [],
            'created_at' => $this->created_at,
        ];
    }
}
