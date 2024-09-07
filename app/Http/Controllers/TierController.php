<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTierRequest;
use App\Models\Tier;
use Illuminate\Http\Request;

class TierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $tiers = Tier::all();
        return view('dashboard.tiers.index', [
            'tiers' => $tiers
        ]);
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
    public function store(StoreTierRequest $request)
    {
        // Validation passed, create a new Tier instance
       Tier::create($request->validated());

        // Return a response
        return to_route('tiers.index')->with('success','Tier Added Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Tier $tier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tier $tier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tier $tier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tier $tier)
    {
        //
    }
}
