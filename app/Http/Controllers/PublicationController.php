<?php

namespace App\Http\Controllers;

use App\Events\PublicationDeleted;
use App\Events\PublicationLiked;
use App\Http\Resources\PublicationResource;
use App\Http\Resources\UserResource;
use App\Models\Publication\Comment;
use App\Models\Publication\Like;
use App\Models\Publication\Publication;
use App\Models\Publication\Specification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Publication/Publications');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Publication/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
            'photo' => ['required', 'image', 'max:2048'],
            'description' => ['required', 'string', 'max:255'],
            'motherboard' => ['nullable', 'string', 'max:255'],
            'cpu' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'graphics' => ['nullable', 'string', 'max:255'],
            'storage' => ['nullable', 'string', 'max:255'],
            'display' => ['nullable', 'string', 'max:255'],
            'keyboard' => ['nullable', 'string', 'max:255'],
            'mouse' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $photo_path = Storage::disk('public')->put('/storage/publications', $request['photo']);

        $publication = Publication::create([
            'user_id' => Auth::id(),
            'photo_path' => $photo_path,
            'description' => $request->description
        ]);

        Specification::create([
            'publication_id' => $publication->id,
            'motherboard'=> $request->motherboard,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'graphics' => $request->graphics,
            'storage' => $request->storage,
            'display' => $request->display,
            'keyboard' => $request->keyboard,
            'mouse' => $request->mouse
        ]);

        return redirect()->route('publications');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $publication = new PublicationResource(Publication::where('id', $id)->withCount('comments')->first());

        return Inertia::render('Publication/Show', [
            'publication' => $publication
        ]);
    }

    /**
     * Show user profile
     */
    public function show_profile(User $user)
    {
        if ($user->id == Auth::id()) {
            // redirect to my-profile
            return redirect()->route('my-profile');
        }

        return Inertia::render('Publication/Publications',[
            'user_profile' => new UserResource($user),
        ]);
    }

    /**
     * Show my profile
     */
    public function show_my_profile()
    {
        return Inertia::render('Publication/Publications',[
            'user_profile' => new UserResource(Auth::user()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $publication
     */
    public function edit(Publication $publication)
    {
        return Inertia::render('Publication/Create', [
           'publication' => new PublicationResource($publication)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Validator::make($request->all(), [
            'publication_id' => ['required'],
            'photo' => ['nullable','image', 'max:2048'],
            'description' => ['required', 'string', 'max:255'],
            'motherboard' => ['nullable', 'string', 'max:255'],
            'cpu' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'graphics' => ['nullable', 'string', 'max:255'],
            'storage' => ['nullable', 'string', 'max:255'],
            'display' => ['nullable', 'string', 'max:255'],
            'keyboard' => ['nullable', 'string', 'max:255'],
            'mouse' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $publication = Publication::find($request->publication_id);

        // update publication
        if ($request->photo != null) {
            // delete old photo
            Storage::disk('public')->delete($publication->photo_path);

            // upload new photo
            $photo_path = Storage::disk('public')->put('/storage/publications', $request->photo);

            $publication->update([
                'photo_path' => $photo_path,
                'description' => $request->description
            ]);

        } else {
            $publication->update([
                'description' => $request->description
            ]);
        }

        $specification = Specification::where('publication_id', $publication->id)->first();

        // update specifications
        $specification->update([
            'motherboard'=> $request->motherboard,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'graphics' => $request->graphics,
            'storage' => $request->storage,
            'display' => $request->display,
            'keyboard' => $request->keyboard,
            'mouse' => $request->mouse
        ]);

        return redirect()->route('my-profile');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $publication = Publication::find($request->publication_id);

        // check if publication exists
        if (isset($publication)) {
            // get comments
            $comments = $publication->comments;
            // if has comments
            if (isset($comments)) {
                foreach ($comments as $comment) {
                    // get replies
                    $replies = $comment->replies;
                    // if has replies
                    if (isset($replies)) {
                        Comment::destroy($replies->modelKeys());
                    }
                }

                Comment::destroy($comments->modelKeys());
            }

            // delete photo
            Storage::disk('public')->delete($publication->photo_path);

            $publication->delete();

            // broadcast to others
            broadcast(new PublicationDeleted($publication->id))->toOthers();
        }

        return redirect()->route('publications');
    }

    /**
     * Fetch Publications
     */
    public function get_publications(Request $request)
    {
        $first_item_created_at = $request->first_item_created_at ? $request->first_item_created_at : now();

        if (is_array($request->publications_ids)) {
            $publications_ids = $request->publications_ids;
        } else {
            $publications_ids = [];
        }

        if ($request->user_profile_id != null) {
            // check if to get only logged in user's publications
            $publications = Publication::where('created_at', '<=', $first_item_created_at)->where('user_id', $request->user_profile_id)->whereNotIn('id', $publications_ids)->with(['specification', 'user'])->withCount('comments', 'likes')->orderByDesc('created_at')->simplePaginate(5);
        } else {
            $publications = Publication::where('created_at', '<=', $first_item_created_at)->whereNotIn('id', $publications_ids)->with(['specification', 'user'])->withCount('comments', 'likes')->orderByDesc('created_at')->simplePaginate(5);
        }

        return response()->json([
            'publications' => PublicationResource::collection($publications),
        ]);
    }

    /**
     *Like publication
     */
    public function like(Request $request)
    {
        Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'publication_id' => ['required', 'integer'],
        ])->validate();

        Like::firstOrCreate(
            [
                'user_id' => $request->user_id,
                'publication_id' => $request->publication_id
            ]
        );

        $publication = Publication::find($request->publication_id);

        if (isset($publication)) {
            broadcast(new PublicationLiked($publication))->toOthers();
        }

        $likes_count = count($publication->likes);

        return response()->json([
            'likes_count' => $likes_count
        ]);
    }

    /**
     *Unlike publication
     */
    public function unlike(Request $request)
    {
        Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'publication_id' => ['required', 'integer'],
        ])->validate();

        $like = Like::where('user_id', $request->user_id)->where('publication_id', $request->publication_id);

        $publication = Publication::find($request->publication_id);

        if (isset($like) || isset($publication)) {

            $like->delete();

            broadcast(new PublicationLiked($publication))->toOthers();
        }

        $likes_count = count($publication->likes);

        return response()->json([
            'likes_count' => $likes_count
        ]);
    }
}
