<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        // Create sample users for each role
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@citizenid.gov',
                'password' => Hash::make('admin124'),
                'role' => 'admin'
            ],
            [
                'name' => 'Registration Officer',
                'email' => 'registrar@citizenid.gov',
                'password' => Hash::make('registrar123'),
                'role' => 'registrar'
            ],
            [
                'name' => 'Authentication Officer',
                'email' => 'auth@citizenid.gov',
                'password' => Hash::make('auth123'),
                'role' => 'auth_officer'
            ],
            [
                'name' => 'Supervisor',
                'email' => 'supervisor@citizenid.gov',
                'password' => Hash::make('supervisor123'),
                'role' => 'supervisor'
            ],
            [
                'name' => 'Printing Officer',
                'email' => 'printing@citizenid.gov',
                'password' => Hash::make('printing123'),
                'role' => 'printing_officer'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'email_verified_at' => now(),
                ]
            );

            $role = Role::where('name', $userData['role'])->first();
            if ($role && $user) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
