<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Admin - Full access to everything
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->permissions()->sync(Permission::all()->pluck('id'));
        }

        // Registration Officer - Citizen registration and basic application management
        $registrar = Role::where('name', 'registrar')->first();
        if ($registrar) {
            $registrarPermissions = Permission::whereIn('name', [
                'register_citizens',
                'edit_citizens',
                'view_citizens',
                'create_applications',
                'view_applications',
                'edit_applications',
            ])->pluck('id');
            $registrar->permissions()->sync($registrarPermissions);
        }

        // Authentication Officer - Document and identity verification
        $authOfficer = Role::where('name', 'auth_officer')->first();
        if ($authOfficer) {
            $authPermissions = Permission::whereIn('name', [
                'view_citizens',
                'view_applications',
                'verify_documents',
                'verify_biometrics',
                'authenticate_identity',
                'review_applications',
            ])->pluck('id');
            $authOfficer->permissions()->sync($authPermissions);
        }

        // Supervisor - Application approval and oversight
        $supervisor = Role::where('name', 'supervisor')->first();
        if ($supervisor) {
            $supervisorPermissions = Permission::whereIn('name', [
                'view_citizens',
                'view_applications',
                'approve_applications',
                'review_applications',
                'supervise_operations',
                'override_decisions',
                'view_staff_performance',
                'view_reports',
            ])->pluck('id');
            $supervisor->permissions()->sync($supervisorPermissions);
        }

        // Printing Officer - ID card production and printing
        $printingOfficer = Role::where('name', 'printing_officer')->first();
        if ($printingOfficer) {
            $printingPermissions = Permission::whereIn('name', [
                'view_citizens',
                'view_applications',
                'print_cards',
                'manage_print_queue',
                'quality_control',
            ])->pluck('id');
            $printingOfficer->permissions()->sync($printingPermissions);
        }
    }
}
