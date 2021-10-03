<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;
use App\Models\Transaction_item;

class TransactionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = Transaction::query();
            return DataTables::of($query)
            ->addColumn('action', function($item){
                return '
                    <a href="'. route('dashboard.transaction.edit', $item->id) .'" class="shadow-lg bg-gray-500 hover:bg-red-700 text-white font-bold rounded">
                        Edit
                    </a>
                    <a href="' . route('dashboard.transaction.show', $item->id) . '" class="shadow-lg bg-gray-500 hover:bg-yellow-700 text-white font-bold rounded">
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Transaction $transaction)
    {

        if (request()->ajax()) {
            $query = Transaction_item::with(['product'])->where('transaction_id', $transaction->id);
            return DataTables::of($query)
            ->editColumn('product.price', function($item){
                return number_format($item->product->price);
            })->rawColumns(['action'])->make();
        }
        // dd("gagal");
        return view('pages.dashboard.transaction.detail', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        return view('pages.dashboard.transaction.edit', ['item'=>$transaction]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Success,Challenge,Failed,Shipping,Shipped'
        ]);
        $data = $request->all();
        $transaction->update($data);

        return redirect()->route('dashboard.transaction.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
