<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokumen;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemsPerPage = request('items') ?? 10;

        $users = User::with(['roles'])
                    ->latest()
                    ->filter(request(['search']))
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view("pages/users/index", [
            "title" => "Data User",
            "users" => $users
        ]);
    }


    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $rolesList = Role::select('id','name')->get();

        return view('pages/users/create', [
            'rolesList' => $rolesList
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['password'] = Hash::make($request['password']);
        
        $user = User::create($data);

        $user->assignRole($request['roles']);

        return redirect()->route('users.index')->with('message','Pengguna berhasil ditambahkan.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\users  $users
    * @return \Illuminate\Http\Response
    */
    public function show(User $user)
    {
        return view('users.show',compact('users'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {
        $rolesList = Role::select('id','name')->get();
        $users = User::with(['roles'])->where('id', $user->id)->first();

        return view('pages/users/edit', [
            'users' => $users,
            'rolesList' => $rolesList,
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\users  $users
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, User $user)
    {

        $data['name'] = $request['name'];
        $data['email'] = $request['email'];
        $data['password'] = Hash::make($request['password']);

        User::where('id', $user->id)->update($data);

        $updatedUser = User::where('id', $user->id)->first();
        $updatedUser->roles()->detach();
        $updatedUser->assignRole($request['roles']);

        return redirect()->route('users.index')->with('message','Pengguna berhasil diperbaharui');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\User  $users
    * @return \Illuminate\Http\Response
    */
    public function destroy(User $user)
    {
        User::destroy($user->id);
        return redirect()->route('users.index')->with('message','Pengguna berhasil dihapus');
    }
}
