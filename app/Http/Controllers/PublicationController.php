<?php

namespace App\Http\Controllers;

use App\Events\PublicationLiked;
use App\Http\Resources\PublicationResource;
use App\Models\Publication\Like;
use App\Models\Publication\Publication;
use App\Models\Publication\Specification;
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
            'user_id' => ['required', 'integer'],
            'photo' => ['required', 'image', 'max:1024'],
            'description' => ['required', 'string', 'max:255'],
            'motherboard' => ['nullable', 'string', 'max:255'],
            'cpu' => ['nullable', 'string', 'max:255'],
            'ram' => ['nullable', 'string', 'max:255'],
            'video_card' => ['nullable', 'string', 'max:255'],
            'storage' => ['nullable', 'string', 'max:255'],
            'monitor' => ['nullable', 'string', 'max:255'],
            'keyboard' => ['nullable', 'string', 'max:255'],
            'mouse' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $photo_path = Storage::disk('ftp')->put('/publications', $request['photo']);

        $publication = Publication::create([
            'user_id' => $request->user_id,
            'photo_path' => $photo_path,
            'description' => $request->description
        ]);

        Specification::create([
            'publication_id' => $publication->id,
            'motherboard'=> $request->motherboard,
            'cpu' => $request->cpu,
            'ram' => $request->ram,
            'video_card' => $request->graphics,
            'storage' => $request->storage,
            'monitor' => $request->display,
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get Publications
     */
    public function get_publications(Request $request)
    {
        $first_item_created_at = $request->first_item_created_at;

        if ($first_item_created_at == null) {
            $first_item_created_at = now();
        }

        $publications = Publication::where('created_at', '<=', $first_item_created_at)->whereNotIn('id', $request->publications_ids)->with(['specification', 'user','likes'])->withCount('comments')->orderByDesc('created_at')->simplePaginate(5);

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

        broadcast(new PublicationLiked($publication))->toOthers();

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

        if (isset($like)) {
            $like->delete();
        }

        $publication = Publication::find($request->publication_id);

        broadcast(new PublicationLiked($publication))->toOthers();

        $likes_count = count($publication->likes);

        return response()->json([
            'likes_count' => $likes_count
        ]);
    }
}
