<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $userIds = collect();
        // Verify roles exist before creating users
        if (Role::where('name', 'admin')->exists()) {
            // Create admin user
            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password$2025'),
                'remember_token' => Str::random(10),
            ]);
            $admin->assignRole('admin');
            $userIds->push($admin->id);
        }
        if (Role::where('name', 'user')->exists()) {
            // Create regular user
            $user = User::create([
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password$2025'),
                'remember_token' => Str::random(10),
            ]);
            $user->assignRole('user');
            $userIds->push($user->id);

            // Create additional test users
            User::factory(5)->create()->each(function ($user) use ($userIds) {
                $user->assignRole('user');
                $userIds->push($user->id);
            });
        }

        NewsPost::factory(100)->state(function () use ($userIds) {
            return [
                'user_id' => $userIds->random(),
            ];
        })->create();

        BlogPost::factory(100)->state(function () use ($userIds) {
            return [
                'user_id' => $userIds->random(),
            ];
        })->create();
    }
}
