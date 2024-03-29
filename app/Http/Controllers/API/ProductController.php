<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products=Product::all();

        return response()->json([
            'status'=>true,
            'message'=>"All Products",
            'data'=>$products
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
    public function store(ProductRequest $request)
    {
        $product =Product::create($request->all());

        if ($product) {
        return response()->json([
            'status'=>true,
            'message'=>"Product Created Successufly",
            'data'=>$product
            
        ]);
        }
        else
        {
        return response()->json([
            'status'=>false,
            'message'=>"Failed to Create Product"       
        ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        if($product){
            return response()->json([
                'status'=>true,
                'message'=>"Product Founded",
                'data'=>$product
                
            ]);
        }
        else{
            return response()->json([
                'status'=>false,
                'message'=>"Product Not Found"
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
    public function update(ProductRequest $request, string $id)
    {
            $product = Product::find($id);

            if($product){

                $validatedData = $request->validated();
            
                $product->fill($validatedData)->save();

                if ($product->wasChanged()) {
                    return response()->json([
                        'status' => true,
                        'message' => "Product Updated Successfully",
                        'data' => $product
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "No changes were made to the product"
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => "Product Not Found"
                ]);
            }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
                
                if ($product) {
                    $deleted = $product->delete();
                    if ($deleted) {
                        return response()->json([
                            'status' => true,
                            'message' => "Product Deleted Successfully",
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message' => "Failed to delete the Product",
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "Product Not Found",
                    ]);
                }
    }
}
