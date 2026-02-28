<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('permission:roles_permissions view', ['only' => ['index']]);
    //     $this->middleware('permission:roles_permissions create', ['only' => ['create', 'store', 'setPermission']]);
    //     $this->middleware('permission:roles_permissions edit', ['only' => ['edit', 'update', 'show', 'setPermission']]);
    //     $this->middleware('permission:roles_permissions delete', ['only' => ['destroy']]);
    // }


    function index()
    {
        $roles = Role::paginate(20);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(StoreRoleRequest $request)
    {

        Role::create($request->validated());
        return redirect()->route('admin.roles.index')->with('success', 'Role Created successful');
    }


    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated());
        return redirect()->route('admin.roles.index')->with('success', 'Role update successful');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successful');
    }


    public function setPermission(Request $request, $id)
    {
        $role = Role::findById($id);
        return view('admin.roles.set_permissions', compact('role'));
    }

    function updatePermission(Request $request)
    {

        $role = Role::find($request->role_id);
        foreach ($role->permissions as $permission) {
            $role->revokePermissionTo($permission);
        }
        if ($request->has('permissions') && is_array($request->permissions)) {
            $permissions = Permission::whereIn('name', $request->permissions)->get();
            $role->syncPermissions($permissions);
        }
        return redirect()->back()->with('success', 'Permission update successful');
    }
}
