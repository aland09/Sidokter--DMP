<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $itemsPerPage = request('items') ?? 10;
        $search = request('search');

        $roles = Role::latest()
                    ->where('name', 'like', '%' .  $search . '%')
                    ->paginate($itemsPerPage)
                    ->withQueryString();

        return view('pages/roles/index', [
            'title' => 'Role User',
            'roles' => $roles
        ]);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('pages/roles/create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'guard_name' => 'required'
        ]);
        
        Role::create($request->post());

        return redirect()->route('roles.index')->with('message','Role user berhasil ditambahkan.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\roles  $roles
    * @return \Illuminate\Http\Response
    */
    public function show(Role $roles)
    {
        return view('roles.show',compact('roles'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Role  $roles
    * @return \Illuminate\Http\Response
    */
    public function edit(Role $role)
    {
        return view('pages/roles/edit', [
            'roles' => $role,
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\roles  $roles
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Role $role)
    {
        $rules = [
            'name' => 'required|max:255',
            'guard_name' => 'required|max:255',
        ];
         
        $validatedData = $request->validate($rules);
         
        Role::where('id', $role->id)->update($validatedData);

        return redirect()->route('roles.index')->with('message','Role user berhasil diperbaharui');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Role  $roles
    * @return \Illuminate\Http\Response
    */
    public function destroy(Role $role)
    {
        Role::destroy($role->id);
        return redirect()->route('roles.index')->with('message','Role user berhasil dihapus');
    }
}
