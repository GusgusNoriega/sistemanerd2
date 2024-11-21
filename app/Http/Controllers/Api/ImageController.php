<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image; // Importa el modelo Image
use Illuminate\Support\Facades\Storage; // Importa Storage si lo usas

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Image::query();
    
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        $images = $query->paginate($request->get('per_page', 15));
    
        return response()->json($images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        $path = $request->file('image')->store('images', 'public');

        $image = Image::create([
            'name' => $request->name,
            'path' => $path,
        ]);

        return response()->json($image, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $image = Image::findOrFail($id);

        return response()->json($image);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
        ]);

        if ($request->has('name')) {
            $image->name = $request->name;
        }

        if ($request->hasFile('image')) {
            // Eliminar la imagen anterior
            Storage::disk('public')->delete($image->path);

            // Almacenar la nueva imagen
            $path = $request->file('image')->store('images', 'public');
            $image->path = $path;
        }

        $image->save();

        return response()->json($image);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        // Eliminar la imagen del almacenamiento
        Storage::disk('public')->delete($image->path);

        $image->delete();

        return response()->json(null, 204);
    }
}