<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // System Administration
            ['name' => 'manage_users', 'description' => 'Can create, edit, and delete users'],
            ['name' => 'manage_roles', 'description' => 'Can assign roles and permissions'],
            ['name' => 'configure_system', 'description' => 'Can configure system settings'],
            ['name' => 'view_reports', 'description' => 'Can view system reports'],
            ['name' => 'view_audit_logs', 'description' => 'Can view audit logs'],
            
            // Citizen Management
            ['name' => 'register_citizens', 'description' => 'Can register new citizens'],
            ['name' => 'edit_citizens', 'description' => 'Can edit citizen information'],
            ['name' => 'view_citizens', 'description' => 'Can view citizen records'],
            ['name' => 'delete_citizens', 'description' => 'Can delete citizen records'],
            
            // Application Management
            ['name' => 'create_applications', 'description' => 'Can create new applications'],
            ['name' => 'view_applications', 'description' => 'Can view applications'],
            ['name' => 'edit_applications', 'description' => 'Can edit applications'],
            ['name' => 'approve_applications', 'description' => 'Can approve/reject applications'],
            ['name' => 'review_applications', 'description' => 'Can review and process applications'],
            
            // Authentication & Verification
            ['name' => 'verify_documents', 'description' => 'Can verify submitted documents'],
            ['name' => 'verify_biometrics', 'description' => 'Can verify biometric data'],
            ['name' => 'authenticate_identity', 'description' => 'Can authenticate citizen identity'],
            
            // Printing & Production
            ['name' => 'print_cards', 'description' => 'Can print ID cards'],
            ['name' => 'manage_print_queue', 'description' => 'Can manage printing queue'],
            ['name' => 'quality_control', 'description' => 'Can perform quality control checks'],
            
            // Supervision
            ['name' => 'supervise_operations', 'description' => 'Can supervise daily operations'],
            ['name' => 'override_decisions', 'description' => 'Can override staff decisions'],
            ['name' => 'view_staff_performance', 'description' => 'Can view staff performance metrics'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}