<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class permissionController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('view permissions')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return Inertia::render('Dashboard/index', [
            'type' => 'permissions',
            'template' => 'permissions',
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function update(Request $request, Role $role)
    {
        if (!Auth::user()->can('edit permissions')) {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        $permissionsData = $request->input('permissions');
        $permissions = [];

        foreach ($permissionsData as $module => $actions) {
            foreach ($actions as $action => $enabled) {
                if ($enabled === true) {
                    $permissions[] = "$action $module";
                }
            }
        }

        $role->syncPermissions($permissions);
        return back()->with('success', 'Permissions updated successfully');
    }
}
