<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
    	$product = Product::with(['galleries'])->latest()->get();

        return view('pages.frontend.index', compact('product'));
    }

    public function details(Request $request)
    {
    	return view('pages.frontend.details');
    }

    public function cart(Request $request)
    {
    	return view('pages.frontend.cart');
    }

    public function success(Request $request)
    {
    	return view('pages.frontend.success');
    }
}
