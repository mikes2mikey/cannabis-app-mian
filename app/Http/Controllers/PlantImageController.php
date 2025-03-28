<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\PlantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PlantImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Plant $plant)
    {
        if (!Auth::user()->isAdmin() && !$plant->users->contains(Auth::id())) {
            abort(403);
        }

        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
            'description' => 'nullable|string|max:255'
        ]);

        $path = $request->file('image')->store('plant-images', 'public');
        
        // Create thumbnail
        $image = Image::make(Storage::disk('public')->path($path));
        $image->fit(300, 300);
        $thumbnailPath = 'plant-images/thumb_' . basename($path);
        Storage::disk('public')->put($thumbnailPath, (string) $image->encode());

        $plant->images()->create([
            'image_path' => $path,
            'thumbnail_path' => $thumbnailPath,
            'description' => $request->description
        ]);

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Image uploaded successfully.');
    }

    public function destroy(PlantImage $image)
    {
        if (!Auth::user()->isAdmin() && !$image->plant->users->contains(Auth::id())) {
            abort(403);
        }

        // Delete the files
        Storage::disk('public')->delete($image->image_path);
        Storage::disk('public')->delete($image->thumbnail_path);

        $plant = $image->plant;
        $image->delete();

        return redirect()->route('plants.show', $plant)
            ->with('success', 'Image deleted successfully.');
    }
} 