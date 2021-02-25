<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Log;

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
        Log::info($data);
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $data = $request->all();
        $gallery = Gallery::findOrFail($id);
        $gallery->update($data);
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
