<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@citizenid.local',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
        
        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);
        
        // Create other demo users
        $registrar = User::create([
            'name' => 'Data Entry Registrar',
            'email' => 'registrar@citizenid.local',
            'password' => Hash::make('registrar123'),
            'email_verified_at' => now(),
        ]);
        
        $supervisor = User::create([
            'name' => 'Approval Supervisor',
            'email' => 'supervisor@citizenid.local',
            'password' => Hash::make('supervisor123'),
            'email_verified_at' => now(),
        ]);
        
        $printer = User::create([
            'name' => 'Printing Officer',
            'email' => 'printer@citizenid.local',
            'password' => Hash::make('printer123'),
            'email_verified_at' => now(),
        ]);
        
        // Assign roles
        $registrarRole = Role::where('name', 'registrar')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $printingRole = Role::where('name', 'printing_officer')->first();
        
        $registrar->roles()->attach($registrarRole);
        $supervisor->roles()->attach($supervisorRole);
        $printer->roles()->attach($printingRole);

        // Assign permissions to roles (if not already done in migration)
        $this->assignPermissions();
    }

    protected function assignPermissions()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $registrarRole = Role::where('name', 'registrar')->first();
        $supervisorRole = Role::where('name', 'supervisor')->first();
        $printingRole = Role::where('name', 'printing_officer')->first();
        
        $permissions = Permission::all();
        
        foreach ($permissions as $permission) {
            // Admin gets all permissions
            if (!$adminRole->permissions->contains($permission->id)) {
                $adminRole->permissions()->attach($permission->id);
            }
            
            // Registrar permissions
            if (in_array($permission->name, ['register_citizens', 'edit_citizens', 'view_citizens']) && 
                !$registrarRole->permissions->contains($permission->id)) {
                $registrarRole->permissions()->attach($permission->id);
            }
            
            // Supervisor permissions
            if (in_array($permission->name, ['view_citizens', 'approve_applications']) && 
                !$supervisorRole->permissions->contains($permission->id)) {
                $supervisorRole->permissions()->attach($permission->id);
            }
            
            // Printing officer permissions
            if (in_array($permission->name, ['view_citizens', 'print_cards']) && 
                !$printingRole->permissions->contains($permission->id)) {
                $printingRole->permissions()->attach($permission->id);
            }
        }
    }
}