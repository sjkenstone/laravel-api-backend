<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title'=>'required',
            'description'=>'required',
            'price'=>'required',
            'is_active'=>'nullable|integer',
            'category_id'=>'nullable|integer',
            'product_image'=>'nullable|image:jpeg,png,gif,svg|max:2048',
        );

        $validator = Validator::make($request->input(), $rules);
        if($validator->fails()){
            return response()->json([
                'error' => true,
                'response' => $validator->errors()
            ], 401);
        }
        
        $data = new Product($request->input());
        $uploadFolder = 'images';

        // $data->title = $request->get('title');

        if($request->hasFile('product_image')){
            
            $image = $request->file('product_image');
            $image_uploaded_path = $image->store($uploadFolder,'public');
            $filename= basename($image_uploaded_path);
            $data['product_image'] = $filename;
        }

        $data->save();

        // return response()->json([
        //     // 'error' => false,
        //     'data' => $data
        // ], 200);
        return $data;
        // return Product::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if($request->hasFile('product_image')){
            $image = $request->file('product_image');
            $image_upload_path = $image->store('images', 'public');
            $fileName = basename($image_upload_path);
            $product['product_image'] = $fileName;
        }

        $product->update($request->input());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Product::destroy($id);
    }
}
