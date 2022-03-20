<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Address;
use App\Models\OrderProduct;


class OrderController extends Controller
{
    function addOrder(Request $req)
    {
        $user = auth()->user()->id;
        $carts = Cart::where('user_id', $user)->get();
        if (count($carts) == 0 || $carts == null) {
            return response()->json(['message' => 'your cart is empty'], 400);
        } else {

            if(!isset($req->address_id)){
                return response()->json(null, 400);
            }
            $address = Address::where('id', $req->address_id)->where('user_id',$user);
            if(!$address->exists()) {
                return response()->json(null, 400);
            }
            $order = new Order();
            $order->user_id = $user;
            $order->address_id = $req->address_id;
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
                Cart::where('user_id', $user)->delete();
            }
            return response()->json(null,201);
        }
    }

    function getOrders()
    {
        $orders = Order::with('user')->get();
        foreach($orders as $order){
            unset($order['user']);
        }
        return response()->json($orders, 200);
    }

    function orderList()
    {
        $user_id = auth()->user()->id;
        $orders = Order::with('product')->where('user_id',  $user_id)->get();
        return response()->json($orders, 200);
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
