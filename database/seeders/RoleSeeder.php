<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        // Create permissions
        $permissions = [
            'view blog',
            'edit blog',
            'view news',
            'edit news',
            'view permissions',
            'edit permissions'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Give all permissions to admin
        $adminRole->givePermissionTo(Permission::all());

        // Give view permissions to user
        $userRole->givePermissionTo([
            'view blog',
            'view news'
        ]);
    }
}
