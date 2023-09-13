<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $permissions = Permission::get();
        return view("pages/roles/create", [
            "title"             => "Data Peran",
            "permissionsList"   => $permissions,
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
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->get('name')]);
        $role->syncPermissions($request->get('permission'));

        activity()
            ->performedOn($role)
            ->event('created')
            ->log('telah melakukan <strong>penambahan data peran</strong> pada sistem');

        return redirect()->route('roles.index')->with('message','Peran pengguna berhasil ditambahkan.');
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
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::get();

        return view('pages/roles/edit', [
            'roles' => $role,
            'rolePermissions' => $rolePermissions,
            'permissionsList' => $permissions,
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
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role->update($request->only('name'));

        $role->syncPermissions($request->get('permission'));

        activity()
            ->performedOn($role)
            ->event('updated')
            ->log('telah melakukan <strong>pengeditan data peran</strong> pada sistem');

        return redirect()->route('roles.index')->with('message','Peran pengguna berhasil diperbaharui');
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

        activity()
            ->performedOn($role)
            ->event('deleted')
            ->log('telah melakukan <strong>penghapusan data peran</strong> pada sistem');

        return redirect()->route('roles.index')->with('message','Peran pengguna berhasil dihapus');
    }
}
