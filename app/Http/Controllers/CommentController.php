<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
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
            'user_id' => ['required', 'integer'],
            'comment' => ['required', 'string', 'max:255'],
            'publication_id' => ['required', 'integer'],
        ])->validate();

        $publication = Publication::find($request->publication_id);

        $comment = new Comment;

        $comment->user()->associate(Auth::user());

        $comment->comment = $request->comment;

        $publication->comments()->save($comment);

        /*$publication->comments()->create([
            'user_id' => $request->user_id,
            'comment' => $request->comment,
            'commentable_id' => $request->publication_id,
            'commentable_type' => 'App\Models\Publication\Publication',
        ]);*/

        return response()->json([
            'comment_count' => count($publication->comments),
        ]);
    }

    /**
     * Fetch comments
     *
     * @param Request $request
     */
    public function show(Request $request)
    {
        return response()->json([
            'comments' => CommentResource::collection(Comment::orderByDesc('created_at')->simplePaginate(10))
        ]);
    }
}
