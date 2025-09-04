<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la caché de permisos y roles
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos
        Permission::create(['name' => 'crear usuarios']);
        Permission::create(['name' => 'editar usuarios']);
        Permission::create(['name' => 'eliminar usuarios']);
        Permission::create(['name' => 'ver categorías']);
        Permission::create(['name' => 'crear categorías']);
        Permission::create(['name' => 'editar categorías']);
        Permission::create(['name' => 'eliminar categorías']);

        // Crear roles y asignar permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(['crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver categorías', 'crear categorías', 'editar categorías', 'eliminar categorías']);

        $userRole = Role::create(['name' => 'usuario']);
        $userRole->givePermissionTo(['ver categorías']);
    }
}
