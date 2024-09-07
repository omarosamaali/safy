<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   

public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $id = Auth::user()->id;
    $profileData = User::find($id);
    $username = $profileData->name;

    $notification = [
        'message' => 'User ' . $username . ' Login Successfully',
        'alert-type' => 'info'
    ];

    $url = '';

    if ($request->user()->role === 'admin') {
        $url = '/dashboard';
    } elseif ($request->user()->role === 'user') {
        $url = '/userhome';
    }

    // Check if $url is empty or not before dispatching SlowJob
    if (!empty($url)) {

        return redirect()->intended($url)->with($notification);
    } else {
        // Handle the case where $url is empty
        // Redirect to a default page or handle accordingly
        return redirect('/')->with('error', 'Invalid URL');
    }
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
