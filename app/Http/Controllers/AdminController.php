<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function NurtiAdmin()
    {

    
        // Pass user, track, and files data to the view
        return view('dashboard.nutriation');
    }
}
