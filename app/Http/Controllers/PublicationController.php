<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeResource;
use App\Http\Resources\PublicationResource;
use App\Models\Like;
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
        $publication = new PublicationResource(Publication::find($id));

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
     *
     */
    public function get_publications()
    {
        return response()->json([
            'publications' => PublicationResource::collection(Publication::with(['specification', 'user','likes'])->orderByDesc('created_at')->paginate(5)),
        ]);
    }

    /**
     *
     */
    public function like(Request $request)
    {
        Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'publication_id' => ['required', 'integer'],
        ])->validate();

        Like::create([
           'user_id' => $request->user_id,
           'publication_id' => $request->publication_id
        ]);

        return response()->json([
            'likes' => count(Publication::find($request->publication_id)->likes)
        ]);
    }

    /**
     *
     */
    public function unlike(Request $request)
    {
        Validator::make($request->all(), [
            'user_id' => ['required', 'integer'],
            'publication_id' => ['required', 'integer'],
        ])->validate();

        Like::where('user_id', $request->user_id)->where('publication_id', $request->publication_id)->delete();

        return response()->json([
            'likes' => count(Publication::find($request->publication_id)->likes)
        ]);
    }
}
