<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator - Full system access'],
            ['name' => 'registrar', 'description' => 'Registration Officer - Handles citizen registration and data entry'],
            ['name' => 'auth_officer', 'description' => 'Authentication Officer - Verifies documents and identity'],
            ['name' => 'supervisor', 'description' => 'Supervisor - Reviews and approves applications'],
            ['name' => 'printing_officer', 'description' => 'Printing Officer - Manages ID card printing and production'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }
}