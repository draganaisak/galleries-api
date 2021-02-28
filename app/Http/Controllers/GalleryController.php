<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Gallery::with(['user', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return response()->json($data);
    }

    public function getMyGalleries() {
        $currentUser = auth()->user()->id;
        $data = Gallery::with(['user', 'images'])
            ->where('user_id', $currentUser)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    public function getAuthorsGalleries($id) {
        $data = Gallery::with(['user', 'images'])
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\CreateGalleryRequest  $request
     * @return \Illuminate\Http\Response
     */

    public function store(CreateGalleryRequest $request)
    {
        $data = $request->validated();
        $gallery = auth()->user()->galleries()->create($data);
        foreach($request->images as $image) {
            $gallery->images()->create($image);
        }

        return response()->json($gallery);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gallery = Gallery::with(['images', 'user'])->findOrFail($id);
        return response()->json($gallery);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGalleryRequest $request, $id)
    {
        $galleryData = [
            'name' => $request->name,
            'description' => $request->description,
        ];
        $gallery = Gallery::with(['images', 'user'])->findOrFail($id);
        $gallery->images()->delete();
        $gallery->update($galleryData);

        foreach($request->images as $image) {
            $gallery->images()->create($image);
        }
        return response()->json($gallery);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        return response()->json($gallery);
    }
}
