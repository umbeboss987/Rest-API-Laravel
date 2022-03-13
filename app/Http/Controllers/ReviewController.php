<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;


class ReviewController extends Controller
{
    function addReview (Request $req, $product_id){
        if(!Product::where('id', $product_id)->exists()) {
            return response(['message' => 'product not found'],404);
        }
        $req->validate([
            'review' => 'required',          
        ]);

            $user_id = auth()->user()->id;
            $text = $req->input('review');
            $review = new Review();
            $review->user_id = $user_id;
            $review->product_id = $product_id;
            $review->review = $text;
            $review->save();         
        return response(null,201)->header('location', 'http://localhost:8000/rest(api/reviews/'. $review->id );
      
    }


    function getReviews (){
        $first_data = array();
        $reviews = Product::select('review.id','review.review', 'review.user_id', 'product.name as product_name', 'product.id as product_id')
        ->join('review' ,'review.product_id', 'product.id')
        ->get();

        foreach ($reviews as $review){
            $first_data['name'] = $review['product_name'];
            $first_data['id'] = $review['product_id'];       
            $review['product'] =  $first_data;  
             unset($review['product_name'], $review['product_id']);                
            
        }
       
        return response()->json($reviews, 200);
    }
}
