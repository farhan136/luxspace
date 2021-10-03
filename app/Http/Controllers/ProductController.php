<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        
        if (request()->ajax()) {
            $query = Product::query();
            return DataTables::of($query)
            ->addColumn('action', function($item){
                
                return '
                    <a href="'. route('dashboard.product.edit', $item->id) .'" class="shadow-lg bg-gray-500 hover:bg-red-700 text-white font-bold rounded">
                        Edit
                    </a>
                    <a href="'. route('dashboard.product.gallery.index', $item->id) .'" class="shadow-lg bg-gray-500 hover:bg-yellow-700 text-white font-bold rounded">
                        gallery
                    </a>
                    <form class="inline-block" action="'. route('dashboard.product.destroy', $item->id) .'" method="post">
                        ' . method_field('delete') . csrf_field() . '
                        <button type="submit" class=" shadow-lg bg-red-500 hover:bg-red-700 text-white font-bold rounded">
                            Delete
                        </button>
                    </form>
                ';
            })
            ->editColumn('price', function($item){
                return number_format($item->price);
            })->rawColumns(['action'])->addIndexColumn()->removeColumn('id')->make();
        }


        return view('pages.dashboard.product.index');
    }

    public function create()
    {
        return view('pages.dashboard.product.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);

        $data = $request->all(); //all akan mengambil field yang sudah ditandai di fillable
        $data['slug'] = Str::slug($request->name); 
        Product::create($data);

        return redirect()->route('dashboard.product.index');
    }

    public function show($id)
    {
        return view('pages.dashboard.product.detail');
    }

    public function edit($id)
    {
        $request = Product::find($id);
        return view('pages.dashboard.product.edit', ['item'=>$request]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required'
        ]);
        $product = Product::find($id);
        $data = $request->all(); //all akan mengambil field yang sudah ditandai di fillable
        $data['slug'] = Str::slug($request->name); 
        $product->update($data);

        return redirect()->route('dashboard.product.index');
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        $product->delete();

        return redirect()->route('dashboard.product.index');
    }
}
