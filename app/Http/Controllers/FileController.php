<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Track;
use Illuminate\Http\Request;
use App\Http\Requests\StoreFileRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateFileRequest;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $files = File::paginate(20);
        return view('dashboard.files.index', [
            'files' => $files
        ]);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.files.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        // Retrieve the uploaded file
        $uploadedFile = $request->file('file');
        $name = $request->input('name');
        $folder = $request->input('folder');

        // Upload the file using the uploadImage method
        $fileName = $this->uploadImage($uploadedFile, 'uploads');

        // Create a new File instance
        File::create([
            'name' => $name,
            'folder' => $folder,
            'path' => "/uploads/$fileName",
        ]);

        // Redirect with success message
        return redirect()->route('files.index')->with('success', 'File uploaded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        $tracks = Track::all();
        return view('dashboard.files.edit',[
            'file'=>$file,
            'tracks'=>$tracks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFileRequest $request, File $file)
    {
        $file->update($request->except('path'));

        $trackIds = $request->input('track_ids', []);

        $file->tracks()->sync($trackIds);

        return redirect()->route('files.index')->with('success', 'File updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
         // Delete the file record from the database
    $file->delete();

    // Unlink the associated file from the storage folder
    if (Storage::exists($file->path)) {
        Storage::delete($file->path);
    }

    return redirect()->back();
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