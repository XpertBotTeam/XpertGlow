<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'carouselable_type' => 'required|in:App\Models\Category,App\Models\SubCategory,App\Models\Product',
            'carouselable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $carousel = Carousel::create([
            'carouselable_type' => $request->carouselable_type,
            'carouselable_id' => $request->carouselable_id,
        ]);

        $image_file = $request->file('image');
        $path_prefix = 'images/carousels';
        $full_path = $image_file->store($path_prefix, 'public');
        $file_name = basename($full_path);

        $image = Image::create([
            'path' => $file_name,
            'imageable_type' => 'App\Models\Carousel',
            'imageable_id' => $carousel->id,
        ]);

        return response()->json(['message' => 'Carousel image uploaded successfully', 'carousel' => $carousel], 201);
    }

    public function destroy($id)
    {
        $carousel = Carousel::find($id);

        if (!$carousel) {
            return response()->json(['error' => 'Carousel image not found'], 404);
        }

        $image = $carousel->image;
        if ($image && Storage::disk('public')->exists('images/carousels/' . $image->path)) {
            Storage::disk('public')->delete('images/carousels/' . $image->path);
        }

        if ($image) {
            $image->delete();
        }

        $carousel->delete();

        return response()->json(['message' => 'Carousel image deleted successfully'], 200);
    }
}
