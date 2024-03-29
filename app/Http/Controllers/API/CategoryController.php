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

        return response()->json([
            'status'=>true,
            'message'=>"Category Created Successufly",
            'data'=>$category
            
        ]);
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
            // Update the category
            $category->update($request->all());

            // Check if the update was successful
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

        return response()->json([
            'status' => false,
            'message' => "Category Not Found"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
                $category = Category::find($id);
                
                if ($category) {
                    $deleted = $category->delete();
                    if ($deleted) {
                        return response()->json([
                            'status' => true,
                            'message' => "Category Deleted Successfully",
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Failed to delete the Category",
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Category Not Found",
                    ]);
                }
    }
}
