<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;


class CartController extends Controller
{
    function addCartItem(Request $req)
    {
        if(!isset($req->product_id) || 
           !isset($req->quantity)){
            return response()->json(null,422);
        }
        $product = Product::select('price')->where('id', $req->product_id)->get();
        if(count($product) == 0){
            return response()->json(["message" => "Product not found"], 404);
        }else{
            $cart = new Cart();
            $user_id = auth()->user()->id;
            $cart->product_id = $req->input('product_id');
            $cart->quantity = $req->input('quantity');
            $cart->user_id = $user_id;
            $cart->sub_total = $cart->quantity * $product[0]['price'];
            $cart->save();
            return response(null, 201);
        }   
    }



    function getUserCart (){
        $user = auth()->user()->id;
        $data = Cart::with('product')->where('user_id', $user)->get();
        foreach($data as $cart){
            unset($cart['user_id']);
            unset($cart['product']['created_at'], $cart['product']['updated_at']);
        }
        return response()->json($data);
    }

    function deleteCartItem($id){
        $cart = Cart::where('id', $id);
        if(!$cart->exists()){
            return response()->json(["message" => "cart not found"],404);
        }else{
            $cart->delete();
            return response()->noContent();
        }
       
    }
}
