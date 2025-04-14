<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index()
    {
        return response()->json(Image::with('package')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
            'section' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id'
        ]);

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $filename);

        $image = Image::create([
            'filename' => $filename,
            'section' => $request->section,
            'package_id' => $request->package_id,
        ]);

        return response()->json(['message' => 'Image uploaded successfully', 'image' => $image]);
    }

    public function show($id)
    {
        $image = Image::with('package')->findOrFail($id);
        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        Storage::delete('public/images/' . $image->filename);
        $image->delete();

        return response()->json(['message' => 'Image deleted']);
    }
}
