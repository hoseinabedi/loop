<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /** 
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(){
        $orders = Order::all();
        return \Response(["status" => "success", "result" =>$orders], 200);
    }

    /** 
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request){
        $order = new Order();
        $order->customer_id = $request->customer_id;
        $order->products = json_encode($request->products);
        $order->total_price = $request->total_price;
        $order->save();
        return \Response(["status" => "success", "result" => $order], 200);
    }

    /** 
    * Display the specified resource.
    *
    * @param  int  $order
    * @return \Illuminate\Http\Response
    */
    public function show(Order $order){
        return \Response(["status" => "success", "result" => $order], 200);
    }

    /** 
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  object  $order
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Order $order){
        $order->update($request->all());
        return \Response(["status" => "success", "result" => $order], 200);
    }

    /** 
    * Remove the specified resource from storage.
    *
    * @param  object  $order
    * @return \Illuminate\Http\Response
    */
    public function destroy(Order $order){
        $order->delete();
        return \Response(["status" => "success", "result" => $order], 200);
    }

    /** 
    * Add product to order
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  object  $order
    * @return \Illuminate\Http\Response
    */
    public function addProductToOrder(Request $request, Order $order){
        if(!$order->paid){
            $prevOrders = json_decode($order->products, true);
            $newProduct = ["product_id" => (int)$request->product_id, "amount" => 1];
            $order->products = array_merge($prevOrders, [$newProduct]);
            $order->update();
            return \Response(["status" => "success", "result" => $order], 200);
        }
        return \Response(["status" => "error", "result" => "Order is paid!"], 400);
    }

    /** 
    * Pay order
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  object  $order
    * @return \Illuminate\Http\Response
    */
    public function pay(Request $request, Order $order){
        $order->paid = true;
        $order->update();
        return \Response(["status" => "success", "result" => $order], 200);
    }
    
}
