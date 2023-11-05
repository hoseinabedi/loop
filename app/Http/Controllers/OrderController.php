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
        $order->total_price = (new PaymentController)->calcPrice($request->products);
        $order->save();
        return \Response(["status" => "success", "result" => $order], 200);
    }

    /** 
    * Display the specified resource.
    *
    * @param  int  $order
    * @return \Illuminate\Http\Response
    */
    public function show($order){
        $order = Order::find($order);
        if(!$order) return \Response(["status" => "error", "result" => "Order not found!"], 404);
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
        if($request->products || $request->total_price){
            $request->merge([
                'total_price' => (new PaymentController)->calcPrice(isset($request->products) ? $request->products : $order->products),
            ]);
        }
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
            $order->total_price = (new PaymentController)->calcPrice($order->products);
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
        if($order->paid){
            return \Response(["status" => "error", "result" => "Order is already paid!"], 400);
        }
        $pay = ["order_id" => $order->id, "customer_email" => $order->customer->email, "value" => $order->total_price];
        $payStatus = false;
        $payment = new PaymentController();
        $response = $payment->paymentProvider($request->provider, $pay);
        if($response->message == "Payment Successful"){
            $order->paid = true;
            $order->update();
            $payStatus = true;
        }
        $payment->addPayment([
            "order_id" => $order->id,
            "customer_id" => $order->customer->id,
            "total_price" => $order->total_price,
            "status" => $payStatus,
            "response_message" => $response->message,
            "paid_ip" => $request->ip()
        ]);
        return \Response(["status" => "success", "result" => $response], 200);
    }
    
}