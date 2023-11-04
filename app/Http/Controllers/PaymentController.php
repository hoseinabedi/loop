<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    
    /**
     * Add payment to database
     * 
     * @param  array  $data
     * @return bool $response
     */
    public function addPayment($payment){
        Payment::create($payment);
        return true;
    }
    
    /**
     * Defining the payment providers
     * 
     * @param string $provider
     * @param array $payInfo
     */
    public function paymentProvider($provider, $payInfo){
        switch ($provider){
            case "Super Payment Provider":
                $response = json_decode(Http::post('https://superpay.view.agentur-loop.com/pay', $payInfo)->body());
                break;
            default:
                $response = ["message" => "Invalid payment provider ($provider)"];
        }
        return $response;
    }
}
