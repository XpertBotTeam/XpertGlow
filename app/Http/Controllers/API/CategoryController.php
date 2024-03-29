<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::all();

        return response()->json([
            'status'=>true,
            'message'=>"All Categories",
            'data'=>$categories
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
    public function store(CategoryRequest $request)
    {
        $category =Category::create($request->all());

        if ($category) {
        return response()->json([
            'status'=>true,
            'message'=>"Category Created Successufly",
            'data'=>$category
            
        ]);
        }
        else
        {
        return response()->json([
            'status'=>false,
            'message'=>"Failed to Create Category"       
        ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if($category){
            return response()->json([
                'status'=>true,
                'message'=>"Category Founded",
                'data'=>$category
                
            ]);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=>"Category Not Found"
            ]);
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);

        if($category){

            $validatedData = $request->validated();
            $category->fill($validatedData)->save();
            
            if ($category->wasChanged()) {
                return response()->json([
                    'status' => true,
                    'message' => "Category Updated Successfully",
                    'data' => $category
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Category Update Failed"
                ]);
            }
        }

        else{
            return response()->json([
                'status' => false,
                'message' => "Category Not Found"
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            try {
                $category = Category::findOrFail($id);

                $relatedProducts = $category->products()->exists();

                if ($relatedProducts) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Cannot delete Category as it is referenced by one or more products.'
                    ], 422);
                }

                $category->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Category Deleted Successfully',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category Not Found',
                ], 404);
            }
    }
}
