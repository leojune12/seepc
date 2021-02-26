<?php

namespace App\Http\Controllers;

use App\Events\PublicationCommentAdded;
use App\Events\PublicationCommentReplyAdded;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ReplyResource;
use App\Models\Publication\Comment;
use App\Models\Publication\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     *Store new comment
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'comment' => ['required', 'string', 'max:255'],
            'publication_id' => ['required', 'integer'],
        ])->validate();

        $publication = Publication::find($request->publication_id);

        $comment = new Comment;

        $comment->user()->associate(Auth::user());

        $comment->comment = $request->comment;

        $publication->comments()->save($comment);

        $data = [
            'publication_id' => $publication->id,
            'comment' => new CommentResource($comment),
            'comments_count' => count($publication->comments)
        ];

        broadcast(new PublicationCommentAdded($data))->toOthers();

        return response()->json($data);
    }

    /**
     *Store new comment
     *
     * @param Request $request
     */
    public function store_reply(Request $request)
    {
        Validator::make($request->all(), [
            'reply' => ['required', 'string', 'max:255'],
            'comment_id' => ['required', 'integer'],
        ])->validate();

        $comment = Comment::find($request->comment_id);

        $reply = new Comment;

        $reply->user()->associate(Auth::user());

        $reply->comment = $request->reply;

        $comment->replies()->save($reply);

        $data = [
            'publication_id' => $comment->commentable_id,
            'comment_id' => $comment->id,
            'reply' => new ReplyResource($reply),
            'replies_count' => count($comment->replies)
        ];

        broadcast(new PublicationCommentReplyAdded($data))->toOthers();

        return response()->json($data);
    }

    /**
     * Fetch comments
     *
     * @param Request $request
     */
    public function show_comments(Request $request)
    {
        $comments = CommentResource::collection(Comment::where('commentable_type', 'App\Models\Publication\Publication')->where('commentable_id', $request->publication_id)->with(['user', 'replies'])->orderByDesc('created_at')->get());

        return response()->json([
            'comments' => $comments
        ]);
    }

    /**
     * Fetch replies
     *
     * @param Request $request
     */
    public function show_replies(Request $request)
    {
        $replies = ReplyResource::collection(Comment::where('commentable_type', 'App\Models\Publication\Comment')->where('commentable_id', $request->comment_id)->with(['user'])->get());

        return response()->json([
            'replies' => $replies
        ]);
    }
}
