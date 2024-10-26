<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::get();
        return response()->json(['message'=>'All Product List','products'=>$product]);
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
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
        ]);


        // if($validation->fails()){
        //     return response()->json([$validation->errors()->all()],400);
        // }
        // else{
        //     $validatedData = $validation->validated();
        //     $product = Product::create($validatedData);
        //     return response()->json(['message'=>'Succesfully Created','product'=>$product],200);
        // }

         $validatedData = $validation->validated();
            $product = Product::create($validatedData);
            return response()->json(['message'=>'Succesfully Created','product'=>$product],200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        $product = Product::findOrFail($id);
        return response()->json(['message'=>'Product Update','product'=>$product],200);


    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrfail($id);
        $validation = Validator::make($request->all(),[
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|numeric',
        ]);


        if($validation->fails()){
            return response()->json([$validation->errors()->all()],400);
        }else{
            $validatedData = $validation->validated();
             $product->update($validatedData);
            return response()->json(['message'=>'Succesfully Updated','product'=>$product],200);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrfail($id);
        $product->delete();
        return response()->json(['message'=>'Delete Succesfully'],200);
    }
}
