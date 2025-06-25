<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = Role::create(['name' => 'admin']); //as admin
        $builderRole = Role::create(['name' => 'builder']);
        $userRole = Role::create(['name' => 'user']);

        $adminUser = User::firstOrCreate([
                    'email' => 'admin@gmail.com'
                ], [
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'role_id' => 1,
                    'password' => Hash::make ('admin@gmail.com'),
                ]);
    }
}
