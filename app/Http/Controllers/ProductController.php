<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;


class ProductController extends Controller
{
    function getProducts (){
        $data= Product::all();
          if(empty($data)){
             return response()->json(['products' => []], 200);
           }
            $allProducts = [];
            foreach($data as $product){
                 $product["uri"] = "http://localhost:8080/products/". $product["id"];
                 array_push($allProducts,$product);
            }
           
        return  response()->json(["products" => $allProducts],200);

    }

    function getProductById($product_id)
    {
        try {
            if (!Product::where('id', $product_id)->exists()) {
                return response()->json(['message' => 'product not found'], 404);
            }

            $product = Product::where('id', $product_id)->first();
            return response($product, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    function getProductsByType(Request $request)
    {
        $products_type = request('type'); 
        try {
            if ($products_type != 'phone' || $products_type != 'computer' || $products_type != 'tablet'){
                return response()->json(['message' => 'malformed query parameter'], 400);
            }
            $data = Product::where('type', $products_type)->get();
            if (empty($data) || is_null($data)) {
                return response()->json(['products' => []], 200);
            } else {
                return response()->json($data, 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }   
    }

    function deleteProductById($product_id)
    {
        try {
            $data = Product::find($product_id);
            if (!$data) {
                return response()->json(null, 404);
            } else {
                $data->delete();
                return response(null, 204);
            }
        } catch (Exception $e) {
            return response()->json(500);
        }
    }

    function updateProduct($product_id, Request $req)
    {
        $input = $req->all();
        try {
            $product = Product::find($product_id);
            if(!$product) {
                return response('Product not found', 404);
            }
            else {
                if (isset($input['name'])) $product->name = $input['name'];
                if (isset($input['price'])) $product->price = $input['price'];
                if (isset($input['description'])) $product->description = $input['description'];
                if (isset($input['type'])) $product->type = $input['type'];
                if (isset($input['photo'])) $product->photo = $input['photo'];
                $product->save();
                return response()->noContent();
            }
        } catch (Exception $e) {
            return response(null,500);
        }
     }


    function addProduct(Request $req){
        if (!isset($req->name) || !isset($req->price) || 
            !isset($req->description) ||
            !isset($req->type) || 
            !isset($req->photo)) {
            return response()->json(null, 422);
        }else{
            $product = new Product();
            $product->name = $req->name;
            $product->price = $req->price;
            $product->description = $req->description;
            $product->type = $req->type;
            $product->photo = $req->photo;
            $product->save();
            return response(null, 201)->header('location', 'http://localhost:8000/api/products/' . $product->id);
        }
    }


    function getProductReviews($product_id) {
        $reviews = Review::select('users.username', 'review.review', 'review.id')
        ->join('users', 'review.user_id', 'users.id')
        ->where('product_id', $product_id)
        ->get();
        if(empty($reviews)){
            return response()->json(null, 204);
        }
        return response()->json($reviews, 200);
    }

    function deleteProductReview($review_id) {

        $review = Review::where('id', $review_id);

        if($review){
            $review->delete();
            return response()->json(null, 204);
        }
        return response(null, 204);
    }
}
