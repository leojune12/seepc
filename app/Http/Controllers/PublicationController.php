<?php

namespace App\Http\Controllers;

use App\Models\Publication\Publication;
use App\Models\Publication\Specification;
use Illuminate\Http\Request;
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
        return Inertia::render('Publication/Publication', [
            'publications' => Publication::all()
        ]);
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
            'photo' => ['required', 'image', 'max:2048'],
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

        return redirect()->route('publication');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
