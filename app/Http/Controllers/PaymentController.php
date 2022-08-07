<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payment_handler(Request $request)
    {
        // return "HALLO";
        // $json = json_decode($request->getContent());
        $json = json_decode(json_encode($request->all()));
        // return $json->order_id;
        $signature_key = hash('sha512', $json->order_id . $json->status_code . $json->gross_amount . env('MIDTRANS_SERVER_KEY'));
        // return $signature_key;
        if($signature_key != $json->signature_key)
        {
            return "ERROR";
            return abort(404);
        }

        //status berhasil
        $order = Order::where('order_id', $json->order_id)->first();
        return $order->update(['status'=>$json->transaction_status]);
    }
}
