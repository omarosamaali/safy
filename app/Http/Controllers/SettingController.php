<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class AboutController extends Controller
{
    public function index()
    {
        $contact = config('about');
        return view('about', ['about' => $contact]);
    }

    public function update(Request $request)
    {
        // Handle the image upload
        // Validation rules
        $rules = [
            'email' => 'required|email|max:255',
            'email_q' => 'required|email|max:255',
            'facebook_link' => 'nullable|url|max:255',
            'twitter_link' => 'nullable|url|max:255',
            'instagram_link' => 'nullable|url|max:255',
            'youtupe_link' => 'nullable|url|max:255',
            'linked_link' => 'nullable|url|max:255',
            'phone_number' => 'required|string|max:15',
            'whats_number' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'saddress' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'brochure' => 'nullable|file|mimes:pdf|max:2048',
        ];

        $request->validate($rules);
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }
        $brochureName = null;
        if ($request->hasFile('brochure')) {
            $brochure = $request->file('brochure');
            $brochureName = time() . '.' . $brochure->getClientOriginalExtension();
            $brochure->move(public_path('brochure'), $brochureName);
        }

        $data = [
            'email' => $request->input('email'),
            'email_q' => $request->input('email_q'),
            'facebook_link' => $request->input('facebook_link'),
            'twitter_link' => $request->input('twitter_link'),
            'instagram_link' => $request->input('instagram_link'),
            'linked_link' => $request->input('linked_link'),
            'youtupe_link' => $request->input('youtupe_link'),
            'phone_number' => $request->input('phone_number'),
            'whats_number' => $request->input('whats_number'),
            'address' => $request->input('address'),
            'vision_ar' => $request->input('vision_ar'),
            'vision_en' => $request->input('vision_en'),
            'mission_ar' => $request->input('mission_ar'),
            'mission_en' => $request->input('mission_en'),
            'quality-ar' => $request->input('quality-ar'),
            'quality-en' => $request->input('quality-en'),
            'discreption1-ar' => $request->input('discreption1-ar'),
            'discreption1-en' => $request->input('discreption1-en'),
            'discreption2-ar' => $request->input('discreption2-ar'),
            'discreption2-en' => $request->input('discreption2-en'),
            'clients_num' => $request->input('clients_num'),
            'projects_num' => $request->input('projects_num'),
            'emp_num' => $request->input('emp_num'),
            'saddress' => $request->input('saddress') ?? Null,
            'about_dis-ar' => $request->input('about_dis-ar'),
            'about_dis-en' => $request->input('about_dis-en'),
            'youtupe_video_link' => $request->input('youtupe_video_link'),
            'abouttitle_ar' => $request->input('abouttitle_ar'),
            'abouttitle_en' => $request->input('abouttitle_en'),
        ];

        if ($imageName) {
            $data['image'] = $imageName;
        } else {
            $about = config('about');
            $data['image'] = $about['image'];
        }
        if ($brochureName) {
            $data['brochure'] = $brochureName;
        } else {
            $about = config('about');
            $data['brochure'] = $about['brochure'];
        }

        $path = config_path('about.php');

        if (File::exists($path)) {
            $content = "<?php\n\nreturn " . var_export($data, true) . ";\n";
            File::put($path, $content);
            Config::set('about', $data);
            Artisan::call('optimize:clear');
            return view('dashboard.about.edit',["about"=>$data])->with('success', 'تم تحديث الاعدادات بنجاح');
        } else {
            Artisan::call('optimize:clear');
            return view('dashboard.about.edit',["about"=>$data])->with('error', 'Contact page configuration file not found.');

        }
    }


    public function edit()
    {
        Artisan::call('optimize:clear');
        $about = config('about');

        return view('dashboard.about.edit',["about"=>$about]);
    }
}
