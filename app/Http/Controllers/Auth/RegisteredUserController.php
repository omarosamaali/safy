<?php

namespace App\Http\Controllers\Auth;

use App\Models\Tier;
use App\Models\User;
use App\Models\Track;
use App\Models\Program;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Retrieve programs from the database
        $programs = Program::with('tier')->get();
        $tracks    = Track::all();
    
        // Initialize an empty array to store program data
        $programData = [];
    
        // Loop through each program and format the data
        foreach ($programs as $program) {
            $tier = $program->tier->name; // Assuming 'tier' is the relationship between Program and Tier models
            $programData[$tier][] = [
                'name' => $program->name,
                'price' => $program->price,
            ];
        }
    
        // Pass the formatted program data to the view
        return view('auth.register', compact('programData','tracks'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone' => 'string',
            'password' => 'required|confirmed',
            'age' => 'nullable|integer|min:0',
            'is_Nutritional_supplements' => 'required|boolean',
            'health_status' => 'nullable|string|max:255',
            'track_id' => 'nullable|integer|min:0',
            'body_image_front' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'body_image_back' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Payment_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'inbody_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'period' => 'required',
            'track_type' => 'required',
            'training_type' => 'required',
            'card_name' => 'required',
            'user_level' => 'required',
        ]);
    
        $bodyImageFront = $this->uploadImage($request->file('body_image_front'), 'body_image_front');
        $bodyImageBack = $this->uploadImage($request->file('body_image_back'), 'body_image_back');
        $Payment_photo = $this->uploadImage($request->file('Payment_photo'), 'Payment_photo');
        $inbody_photo = $this->uploadImage($request->file('inbody_photo'), 'inbody_photo');
        $default_image = '/userplace.jpg';
    
        // Extract period duration from the 'period' input using regex
        preg_match('/^\d+ Week/', $request->period, $periodMatches);
        $periodDuration = $periodMatches[0]; // e.g., "12 Week"
    
        // Extract the word before the parentheses in card_name
        preg_match('/^[^\(]+/', $request->card_name, $cardNameMatches);
        $cardName = trim($cardNameMatches[0]); // e.g., "Gold"
    
        // Fetch the program ID based on the extracted period duration
        $program = Program::where('name', $periodDuration)->first();
    
        // Fetch the tier ID based on the card_name
        $tier = Tier::where('name', $cardName)->first();
    
        // Find the track ID based on user_level, training_type, and program_id
        $track = Track::where([
            ['user_level', '=', $request->user_level],
            ['training_type', '=', $request->training_type],
            ['program_id', '=', $program->id]
        ])->first();
    
        $user = User::create([
            'name' => $request->first_name,
            'lname' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $default_image,
            'age' => $request->age,
            'phone' => $request->phone,
            'train_type' => $request->train_type,
            'is_Nutritional_supplements' => $request->is_Nutritional_supplements,
            'health_status' => $request->health_status,
            'track_id' => $track->id,
       
            'body_image_front' => $bodyImageFront,
            'body_image_back' => $bodyImageBack,
            'Payment_photo' => $Payment_photo,
            'inbody_photo' => $inbody_photo,
        ]);
    
        event(new Registered($user));
        Auth::login($user);
    
        return redirect()->route('userhome');
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