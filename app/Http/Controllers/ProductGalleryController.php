<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product_gallerie;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductGalleryController extends Controller
{
    public function index(Product $product)
    {
        if (request()->ajax()) {
            $query = Product_gallerie::query();
            return DataTables::of($query)
            ->addColumn('action', function($item){

                return '
                <form class="inline-block" action="'. route('dashboard.gallery.destroy', $item->id) .'" method="post">
                ' . method_field('delete') . csrf_field() . '
                <button type="submit" class=" shadow-lg bg-red-500 hover:bg-red-700 text-white font-bold rounded">
                Delete
                </button>
                </form>
                ';
            })
            ->editColumn('url', function($item){
                return '<img  style="max-width:150px;" src="'. Storage::url($item->url) .'">';
            })
            ->editColumn('is_featured', function($item){
                return $item->is_featured ? 'Yes': 'No';
            })
            ->rawColumns(['action', 'url'])->addIndexColumn()->removeColumn('id')
            ->make();
        }


        return view('pages.dashboard.gallery.index', compact('product'));
    }

    public function create(Product $product)
    {
        return view('pages.dashboard.gallery.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        // dd($product);
        $files = $request->file('files');

        if ($request->hasFile('files')) { //memisah array menjadi nilai tersendiri
            foreach ($files as $file) {
                $path = $file->store('public/gallery');
                // dd($path);

                Product_gallerie::create([
                    'product_id'=> $product->id,
                    'url'=> $path,
                    'is_featured'=>$request->is_featured?: 0
                ]);
            }
        }
        return redirect()->route('dashboard.product.gallery.index', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product_gallerie $gallery)
    {
        $gallery->delete();
        return redirect()->route('dashboard.product.gallery.index', $gallery->product_id);    
    }
}
