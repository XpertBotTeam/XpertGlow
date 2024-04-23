<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SubCategory;


class SubCategoryController extends Controller
{
    
    public function index()
    {
        $subcategories = SubCategory::all();
        return response()->json(['subcategories' => $subcategories], 200);
    }

    public function show($id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }
        return response()->json(['subcategory' => $subcategory], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $subcategory = SubCategory::create($request->all());
        return response()->json(['subcategory' => $subcategory], 201);
    }

    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $subcategory->update($request->all());
        return response()->json(['subcategory' => $subcategory], 200);
    }

    public function destroy($id)
    {
        $subcategory = SubCategory::find($id);

        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subcategory->delete();
        return response()->json(['message' => 'Subcategory deleted successfully'], 200);
    }


}
