<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;
use App\Models\Transaction_item;
use Illuminate\Support\Facades\Auth;

class MyTransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::where('user_id', Auth::user()->id);
            return DataTables::of($query)
            ->addColumn('action', function($item){
                return '
                    <a href="' . route('dashboard.mytransaction.show', $item->id) . '" class="shadow-lg bg-gray-500 hover:bg-yellow-700 text-white font-bold rounded">
                        Show
                    </a>
                ';
            })
            ->editColumn('total_price', function($item){
                return number_format($item->total_price);
            })->rawColumns(['action'])->addIndexColumn()->removeColumn('id')->make();
        }
        return view('pages.dashboard.transaction.index');
    }

    public function show(Transaction $mytransaction)
    {
        
        if (request()->ajax()) {
            $query = Transaction_item::with(['product'])->where('transaction_id', $mytransaction->id);
            return DataTables::of($query)
            ->editColumn('product.price', function($item){
                return number_format($item->product->price);
            })->rawColumns(['action'])->make();
        }
        // dd("gagal");
        return view('pages.dashboard.transaction.detail', ['transaction'=>$mytransaction]);

    }

}
