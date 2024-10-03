<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Controllers\API\BaseController;

class RoleController extends BaseController

{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('get-role')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        try {
            $roles = Role::all();
            return $this->sendResponse($roles, 'All the Roles Retrieved Successfully');
        } catch (\Exception $e) {
            return $this->sendError('Something went wrong..', $e->getMessage());
        }
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name',
    ]);

    if (!auth()->user()->hasPermissionTo('add-role')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $role = Role::create([
        'name' => $request->name,
        'guard_name' => 'web',
    ]);

    return $this->sendResponse($role, 'Role Added Successfully');
}



public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:roles,name,' . $request->id,
    ]);

    if (!auth()->user()->hasPermissionTo('update-role')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $role = Role::findOrFail($request->id);
    
    $role->update($request->only(['name'])); 
    return $this->sendResponse($role, 'Role Updated Successfully');
}


public function destroy(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:roles,id',
    ]);

    if (!auth()->user()->hasPermissionTo('delete-role')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    try {
        $id = $request->id;

        $deleted = Role::destroy($id);
        
        if ($deleted) {
            return $this->sendResponse(null, 'Role deleted successfully');
        } else {
            return $this->sendError('Role not found');
        }
    } catch (\Exception $e) {
        return $this->sendError('Error deleting role', $e->getMessage());
    }
}

public function assignPermissions(Request $request)
{
    $request->validate([
        'id' => 'required|exists:roles,id',
        'permissions' => 'required|exists:permissions,id'
    ]);

    if (!auth()->user()->hasPermissionTo('assign-role')) {
        return $this->sendError('Forbidden', [], 403);
    }

    try {
        $role = Role::findOrFail($request->id);
        $permissionIds = $request->input('permissions', []);

        $permissions = Permission::whereIn('id', $permissionIds)->pluck('id')->toArray();

        $role->syncPermissions($permissions);

        return $this->sendResponse($role->permissions, 'Permissions assigned successfully.');
    } catch (\Exception $e) {
        return $this->sendError('An error occurred while assigning permissions.', [$e->getMessage()], 500);
    }
}

}
