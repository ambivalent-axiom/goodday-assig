<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CurrentUserRoleController extends Controller
{
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::findOrFail($id);

        $roleIds = Role::whereIn('name', $validated['roles'])
            ->pluck('id');

        $user->roles()->sync($roleIds);

        return back(303);
    }
}
