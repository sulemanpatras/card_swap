<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Http\Controllers\API\BaseController;

class PermissionController extends BaseController

{
    public function index()
    {

        if (!auth()->user()->hasPermissionTo('permission-get')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        try {
            $permission = Permission::all();

            return $this->sendResponse($permission, 'Permission retrieved successfully');
        } catch (\Exception $e) {
            return   $this->sendError('Something went wrong..', $e->getMessage());
        }
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:permissions,name',
    ]);

    if (!auth()->user()->hasPermissionTo('permission-add')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $permission = Permission::create([
        'name' => $request->name,
        'guard_name' => 'web',
    ]);

    return $this->sendResponse($permission, 'Permission Created Successfully');
}




public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:permissions,name,' . $request->id,
    ]);

    if (!auth()->user()->hasPermissionTo('permission-update')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $permission = Permission::findOrFail($request->id);
    
    $permission->update($request->only(['name'])); 
    return $this->sendResponse($permission, 'Permission Updated Successfully');
}



public function destroy(Request $request)
{
    $request->validate([
        'id' => 'required|integer|exists:permissions,id',
    ]);

    if (!auth()->user()->hasPermissionTo('permission-delete')) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    try {
        $id = $request->id;

        $permission = Permission::findOrFail($id);
        $permission->delete();

        return $this->sendResponse(null, 'Permission deleted successfully');
    } catch (\Exception $e) {
        return $this->sendError('Something went wrong..', $e->getMessage());
    }
}
}
