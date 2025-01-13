<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarkerController extends Controller
{
    public function storeMarker(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
            'cuisine_type' => 'required|string',
            'price_range' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'operating_hours' => 'required|string',
            'image' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('restaurants', 'public');
        }

        $marker = Marker::create([
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'description' => $request->description,
            'cuisine_type' => $request->cuisine_type,
            'price_range' => $request->price_range,
            'rating' => $request->rating,
            'operating_hours' => $request->operating_hours,
            'image_path' => $imagePath ?? null
        ]);

        return response()->json($marker);
    }

    public function getMarkers()
    {
        $markers = Marker::all();
        return response()->json($markers);
    }

    public function updateMarker(Request $request, $id)
    {
        $marker = Marker::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
            'cuisine_type' => 'required|string',
            'price_range' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'operating_hours' => 'required|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($marker->image_path) {
                Storage::disk('public')->delete($marker->image_path);
            }
            $data['image_path'] = $request->file('image')->store('restaurants', 'public');
        }

        $marker->update($data);
        return response()->json(['message' => 'Marker updated successfully']);
      }

    public function deleteMarker($id)
    {
        $marker = Marker::findOrFail($id);
        if ($marker->image_path) {
            Storage::disk('public')->delete($marker->image_path);
        }
        $marker->delete();
        return response()->json(['message' => 'Marker deleted']);
    }
    public function showDirections($id)
    {
        $marker = Marker::findOrFail($id);
        return view('directions', compact('marker'));
    }
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'description' => 'nullable|string',
        'cuisine_type' => 'nullable|string|max:100',
        'price_range' => 'nullable|string|in:$,$$,$$$,$$$$',
        'rating' => 'nullable|numeric|min:1|max:5',
        'operating_hours' => 'nullable|string|max:255'
    ]);

    $marker = Marker::create($validatedData);

    return response()->json($marker, 201);
}
}

class InteractiveController extends Controller
{
    public function index()
    {
        $markers = Marker::all();
        return view('interactive', compact('markers'));
    }
}