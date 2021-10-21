<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class APIMidtransController extends Controller
{
    public function callback()
    {
    	//konfigurasi midtrans
        \Midtrans\Config::$serverKey = "SB-Mid-server-2dxzfgFWvrniqU1v_q4-tRu6";
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

    	//buat instance midtrans notifiication
    	$notification = new Notification();

    	//assign ke variable untuk memudahkan coding
    	$status = $notification->transaction_status;
    	$type = $notification->payment_type;
    	$fraud = $notification->fraud->status;
    	$order_id = $notification->order_id;

    	//get transaction id
    	$order = explode('-', $order_id);

    	//cari transaksi berdasarkan id
    	$transaction = Transaction::findOrFail($order[1]);

    	//handle notofication status midtrans
    	if ($status == 'capture') {
    		if($fraud == 'challenge'){
    			$transaction->status = 'PENDING';
    		}else{
    			$transaction->status = 'SUCCESS';
    		}
    	}
    	elseif ($status == 'settlement') {
    		$transaction->status = 'SUCCESS';
    	}elseif ($status == 'pending') {
    		$transaction->status = 'PENDING';
    	}elseif ($status == 'deny') {
    		$transaction->status = 'PENDING';
    	}elseif ($status == 'expire') {
    		$transaction->status = 'CANCELLED';
    	}elseif ($status == 'cancel') {
    		$transaction->status = 'CANCELLED';
    	}

    	//simpan transaksi
    	$transaction->save();

    	//return response untuk midtrans
    	return response()->json([
    		'meta' => [
    			'code'=>200,
    			'message'=>'MIDTRANS NOTIFICATION SUCCESS!'
    		]
    	]);
    }
}
