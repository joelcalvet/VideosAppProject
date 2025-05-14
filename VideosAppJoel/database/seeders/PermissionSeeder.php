<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $this->create_permissions();
        $this->create_roles();
    }

    private function create_permissions()
    {
        $permissions = [
            'manage videos',
            'edit videos',
            'delete videos',
            'manage users',
            'manage series',
            'create videos',
            'view-notification',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }

    private function create_roles()
    {

        $superAdminRole = Role::firstOrCreate([
                    'name' => 'super-admin',
                    'guard_name' => 'web'
                ]);

        $superAdminRole->syncPermissions(Permission::all());

    }
}
