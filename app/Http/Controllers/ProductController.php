<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::with('variants')->get();
        return view('list-product',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('add-product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'moreFields.0.size' => 'required',
            'moreFields.0.color' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
           ]);
        if($validation->fails()){
            return response()->json([
                'message'   => $validation->errors()->all(),
               ]);
        }
        $image = $request->file('file');
        $product = new Product;
        $product->title = $request->title;
        $product->description = $request->description;
        if($image){
            $new_name = $image->getClientOriginalName();
            $product->image = $new_name;
        }
        $product->save();
        $lastId = $product->id;
        $dynamicVariants = $request->moreFields;
        if($dynamicVariants){
            foreach($dynamicVariants as $value){
                $variant = new Variant;
                $variant->product_id = $lastId;
                $variant->size = $value['size'];
                $variant->color = $value['color'];
                $variant->save();
            }
        }

        return response()->json([
            'message'   => 'success',
           ]);

    }

    function action(Request $request)
    {
     $validation = Validator::make($request->all(), [
      'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
     ]);
     if($validation->passes())
     {
      $image = $request->file('file');
      $new_name = $image->getClientOriginalName();
      $image->move(public_path('images'), $new_name);
      return response()->json([
       'message'   => 'success',
       'uploaded_image' => '<img src="/images/'.$new_name.'" class="img-thumbnail" width="50" height="50" />',
       'class_name'  => 'alert-success'
      ]);
     }
     else
     {
      return response()->json([
       'message'   => $validation->errors()->all(),
       'uploaded_image' => '',
       'class_name'  => 'alert-danger'
      ]);
     }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $products = Product::with('variants')->where('product.id',$id)->get();
        return view('edit-product',compact('products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'file' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
           ]);
        if($validation->fails()){
            return response()->json([
                'message'   => $validation->errors()->all(),
               ]);
        }
        $image = $request->file('file');
        $product = Product::with('variants')->find($id);
        $product->title = $request->title;
        $product->description = $request->description;
        if($image){
            $new_name = $image->getClientOriginalName();
            $product->image = $new_name;
        }
        $product->update();
        foreach ($request->moreUpdate as $key => $value) {
            if(isset($value['id']) && $value['id']){
                Variant::where('id', $value['id'])->update($value);
            }
        }
        foreach ($request->moreFields as $ke => $val) {
            $val['product_id'] = $id;
            if($val){
                Variant::create($val);
            }
        }
    
        return response()->json([
            'message'   => 'success',
           ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Product::find($id)->delete();
  
        return response()->json(['success'=>'User Deleted Successfully!']);
    }
}
