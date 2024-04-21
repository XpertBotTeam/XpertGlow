<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imageable_type' => 'required|in:App\Models\Category,App\Models\SubCategory,App\Models\Product,App\Models\Carousel',
            'imageable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $image_file = $request->file('image');
        $path_prefix = 'images/';

        switch ($request->input('imageable_type')) {
            case 'App\Models\Category':
                $path_prefix .= 'categories/';
                break;
            case 'App\Models\SubCategory':
                $path_prefix .= 'subcategories/';
                break;
            case 'App\Models\Product':
                $path_prefix .= 'products/';
                break;
            case 'App\Models\Carousel':
                $path_prefix .= 'carousels/';
                break;
            default:
                $path_prefix .= 'others/';
        }

        $full_path = $image_file->store($path_prefix, 'public');
        $file_name = basename($full_path);

        $image = Image::create([
            'path' => $file_name,
            'imageable_type' => $request->imageable_type,
            'imageable_id' => $request->imageable_id,
        ]);

        return response()->json(['message' => 'Image uploaded successfully', 'image' => $image], 201);
    }

    public function destroy($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $file_path = "images/";
        switch ($image->imageable_type) {
            case 'App\Models\Category':
                $file_path .= 'categories/';
                break;
            case 'App\Models\SubCategory':
                $file_path .= 'subcategories/';
                break;
            case 'App\Models\Product':
                $file_path .= 'products/';
                break;
            case 'App\Models\Carousel':
                $file_path .= 'carousels/';
                break;
            default:
                $file_path .= 'others/';
        }

        $file_path .= $image->path;

        if (Storage::disk('public')->exists($file_path)) {
            Storage::disk('public')->delete($file_path);
        }

        $image->delete();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}
