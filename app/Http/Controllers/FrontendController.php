<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Transaction_item;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
    	$product = Product::with(['galleries'])->latest()->get();

        return view('pages.frontend.index', compact('product'));
    }

    public function details(Request $request)
    {
    	// $product = Product::with(['galleries'])->where('slug', $request->slug)->firstOrFail();
        $product = Product::where('slug', $request->slug)->firstOrFail();
        $recomendations = Product::inRandomOrder()->limit(4)->get();
        // dd($recomendations);
        return view('pages.frontend.details', compact('product', 'recomendations'));
    }

    public function cart(Request $request)
    {
    	$cart = Cart::where('user_id', Auth::user()->id)->get();
        // dd($cart[0]->product);
        return view('pages.frontend.cart', compact('cart'));
    }

    public function cartAdd(Request $request, $id)
    {
        Cart::create([
            'user_id'=>Auth::user()->id,
            'product_id'=>$id
        ]);

        return redirect('/cart');
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'phone' => 'required',
        ]);
        $data = $request->all();

        //get carts data
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        //add to transaction data
        $data['user_id'] = Auth::user()->id;
        $data['total_price'] = $carts->sum('product.price');

        //create transaction
        $transaction = transaction::create($data);

        //create transaction item
        foreach ($carts as $cart) {
            $items[] = Transaction_item::create([
                'transaction_id'=>$transaction_id,
                'user_id'=>$cart->user_id,
                'product_id'=>$cart->product_id
            ]);
        }

        //delete cart after transaction
        Cart::where('user_id', Auth::user()->id)->delete();

        //konfigurasi midtrans
        Config::$serverday = config('services.midtrans.serverkey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        //setup variable midtrans
        $midtrans = [
            'transaction_details'=> [
                'order_id'=>'LUX-'.$transaction_id,
                'gross_amount' => (int) $transaction->total_price
            ],
            'customer_details' => [
                'first_name'=>$transaction->name,
                'email'=>$transaction->email
            ],
            'enabled_payment'=>['gopay', 'bank_transfer'],
            'vtweb'=>[]
        ];

        //payment process
        try {
                // Get Snap Payment Page URL
                $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;
                $transaction->payment_url = $paymentUrl;
                $transaction->save();
                // Redirect to Snap Payment Page
                return redirect($paymentUrl);
                }
                catch (Exception $e) {
                  echo $e->getMessage();
                }
        }

    public function cartDelete(Request $request, $id)
    {
        $item = Cart::findOrFail($id);

        $item->delete();
        
        return redirect('/cart');
    }



    public function success(Request $request)
    {
    	return view('pages.frontend.success');
    }
}
