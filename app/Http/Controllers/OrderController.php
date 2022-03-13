<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    function addOrder(Request $req)
    {
        $user = auth()->user()->id;
        $carts = Cart::where('user_id', $user)->get();
        if (empty($carts) || $carts == null) {
            return response()->json(['message' => 'your cart is empty'], 400);
        } else {
            $order = new Order();
            $order->user_id = $user;
            $order->address_id = $req->input('address_id');
            $order->code = rand(1000, 1999);
            foreach ($carts as $cart) {
                $order->total += $cart->sub_total;
            }
            $order->save();
            foreach ($carts as $cart) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $cart['product_id'];
                $orderProduct->quantity = $cart['quantity'];
                $orderProduct->save();
                DB::table('cart')->where('user_id', $user)->delete();
            }
        }
        return;
    }

    function getOrders()
    {
        $orders = Order::with('user')->get();
        return response()->json($orders, 200);
    }

    function orderList()
    {
        $user_id = JWTAuth::user()->id;
        $orders = Order::with('product')->where('user_id',  $user_id)->get();
        return response()->json($orders);
    }


    function deleteOrderById($order_id)
    {
        $order = Order::where('id',$order_id);
        if ($order) {
            $order->delete();
            return response()->noContent();
        }
        return response()->json(null, 404);
    }
}
