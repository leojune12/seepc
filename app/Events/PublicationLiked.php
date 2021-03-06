<?php

namespace App\Events;

use App\Http\Resources\LikeResource;
use App\Models\Publication\Like;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PublicationLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $publication_id;
    public $likes_count;
    public $liked;
    public $current_user_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($publication)
    {
        $this->publication_id = $publication->id;
        $this->likes_count = count($publication->likes);
        $this->liked = Like::where('publication_id', $publication->id)->where('user_id', Auth::id())->get()->isNotEmpty();
        $this->current_user_id = Auth::id();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('publications');
    }
}
