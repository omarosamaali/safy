<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Track;
use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTrackRequest;

class TrackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tracks = Track::all();
        return view('dashboard.tracks.index', [
            'tracks' => $tracks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programs = Program::all();
        return view('dashboard.tracks.create', [
            'programs' => $programs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreTrackRequest $request)
    {
        // Check if a similar track already exists
    

        // Create a new Track instance
        Track::create($request->all());

        // Redirect with success message
        return redirect()->route('tracks.index')->with('success', 'Track added successfully');
    }


    /**
     * Display the specified resource.
     */
    public function show(Track $track)
    {
        $files = File::all(); // Assuming you have a File model
    
        return view('dashboard.tracks.show', [
            'track' => $track,
            'files' => $files,
        ]);
    }

    public function track_file(Track $track) {

        $files = File::all();
        return view('dashboard.tracks.track_file',[
            'track' => $track,
            'files' => $files,

        ]);



    }


    public function syncFiles(Request $request, Track $track)
    {
        $track->files()->sync($request->input('files'));

        return redirect()->route('tracks.show',["track"=>$track->id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Track $track)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Track $track)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Track $track)
    {
        $track->delete();
        
        return redirect()->back();
    
    }
}
