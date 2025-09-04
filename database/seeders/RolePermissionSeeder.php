<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Crear permisos si no existen
            $permissions = [
                'ver usuarios',
                'crear usuarios',
                'editar usuarios',
                'eliminar usuarios',
                'ver posts',
                'crear posts',
                'editar posts',
                'eliminar posts'
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }

            // Crear roles si no existen
            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $userRole = Role::firstOrCreate(['name' => 'user']);

            // Asignar permisos a roles
            $adminRole->syncPermissions(Permission::all());
            $userRole->syncPermissions(['ver posts']);

            // Asignar rol a un usuario de prueba
            $adminUser = \App\Models\User::where('email', 'admin@example.com')->first();
            if ($adminUser) {
                $adminUser->assignRole('admin');
            }
        });
    }
}
