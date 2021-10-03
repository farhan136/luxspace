<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();
            return DataTables::of($query)
            ->addColumn('action', function($item){
                
                return '
                    <a href="'. route('dashboard.user.edit', $item->id) .'" class="shadow-lg bg-gray-500 hover:bg-red-700 text-white font-bold rounded">
                        Edit
                    </a>
                    <form class="inline-block" action="'. route('dashboard.user.destroy', $item->id) .'" method="post">
                        ' . method_field('delete') . csrf_field() . '
                        <button type="submit" class=" shadow-lg bg-red-500 hover:bg-red-700 text-white font-bold rounded">
                            Delete
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['action'])->addIndexColumn()->removeColumn('id')->make();
        }


        return view('pages.dashboard.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show(User $user)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('pages.dashboard.user.edit', ['item'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'required|in:ADMIN,USER',
            'email' => 'required',
            'name' => 'required'
        ]);
        $data = $request->all();
        $user->update($data);

        return redirect()->route('dashboard.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {  
        $user->delete();

        return redirect()->route('dashboard.user.index');
    }
}
