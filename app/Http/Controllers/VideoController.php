<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Helpers\YoutubeHelper;

class VideoController extends Controller
{
    /*
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::all();
        return view('dashboard.videos.index',compact('videos'));

    
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.videos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        // Transform the YouTube link into an embeddable URL
        $embedLink = YoutubeHelper::embed($request->link);

        // Create a new video instance
        $video = new Video();
        $video->name = $request->name;
        $video->link = $embedLink; // Save the embeddable URL
        $video->save();

        return redirect()->route('videos.index');
    }
    public function toggleActive(Video $video)
    {
        $video->is_active = !$video->is_active;
        $video->save();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }
    public function setOrder(Request $request, $videoId)
    {
        $validatedData = $request->validate([
            'order' => 'required|integer|min:1'
        ]);
    
        $video = Video::findOrFail($videoId);
        $video->update(['order' => $validatedData['order']]);
    
        return redirect()->back()->with('success', 'Order updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();
        
        return redirect()->back();
    }
}
