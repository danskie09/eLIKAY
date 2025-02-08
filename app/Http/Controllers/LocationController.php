<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    //
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|string',
            'description' => 'nullable|string'
        ]);

        $location = Location::create([
            ...$validated,
            'user_id' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Location added successfully!');
    }

    public function index()
    {
        $locations = Location::all();
        return response()->json($locations);
    }
}
