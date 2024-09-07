<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Track;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource
     */
    public function index()
    {
        $users = User::where('role', 'user')->latest()->paginate(10);
        return view('dashboard.users.index', [
            'users' => $users
        ]);
    }
    
    public function pendingUsers()
    {
        $users = User::where('role', 'user')
                     ->where('is_accepted', false)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
    
        return view('dashboard.users.pendingUser', [
            'users' => $users
        ]);
    }
    

    public function toggleAcceptedStatus(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Toggle the value of is_accepted
        $user->is_accepted = !$user->is_accepted;
        $user->save();

        return redirect()->back();
    }


    public function userHome()
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get the track related to the authenticated user
        $track = $user->track;
    
        // If track is found
        if ($track) {
            // Load files related to the track
            $files = $track->files()->get();
        } else {
            // If no track found, set files to empty collection
            $files = collect();
        }
    
        // Pass user, track, and files data to the view
        return view('users.home', compact('user', 'track', 'files'));
    }
    public function userFiles()
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Get the track related to the authenticated user
        $track = $user->track;
    
        // If track is found
        if ($track) {
            // Load files related to the track
            $files = $track->files()->get();
        } else {
            // If no track found, set files to empty collection
            $files = collect();
        }
    
        // Pass user, track, and files data to the view
        return view('users.files', compact('user', 'track', 'files'));
    }


    public function userVideos()
    {
        // Get the authenticated user
        $user = Auth::user();
    
       $videos = Video::all();
    
        // Pass user, track, and files data to the view
        return view('users.videos', compact('user', 'videos'));
    }


    public function Nurti()
    {

    
        // Pass user, track, and files data to the view
        return view('users.nutriation');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request) {





     }


public function upload_photo(Request $request)
{
    // Get the uploaded photo path
    $photoPath = $this->uploadImage($request->file('Payment_photo'), 'Payment_photo');

    // Update or create a photo record
    $user = auth()->user(); // Assuming authenticated user
    $user->update(['Payment_photo' => $photoPath]);

    return redirect()->back()->with('success', 'Photo uploaded successfully.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = User::find($id);
        $tracks = Track::all();

        return view('dashboard.users.show', [
            'user' => $user,
            'tracks' => $tracks
        ]);


        
    }

    public function selectTrack(Request $request, $userId)
    {
        // Retrieve the user based on the $userId
        $user = User::find($userId);
    

    
        // Validate the track_id provided in the request
        $request->validate([
            'track_id' => 'required|exists:tracks,id',
        ]);
    
        // Update the track_id for the specified user
        $user->update(['track_id' => $request->track_id]);
    
        // Redirect back or to any other page
        return redirect()->back();
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    } 


    private function uploadImage($image, $folder)
    {
        if ($image) {
            $imageName = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path("/$folder");
            $image->move($path, $imageName);
            return $imageName;
        }
        return null;
    }
}
