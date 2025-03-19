<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear el rol de administrador si no existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Crear algunos permisos (si no existen)
        $permissions = ['crear usuarios', 'editar usuarios', 'eliminar usuarios', 'ver reportes'];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al rol de administrador
        $adminRole->syncPermissions(Permission::all());

        // Crear un usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'soportens.2024@gmail.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make(',.nsitpbx.,'),
            ]
        );

        // Asignarle el rol de administrador
        $admin->assignRole('admin');
    }
}
