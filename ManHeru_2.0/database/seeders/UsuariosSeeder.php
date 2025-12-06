<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla
        DB::table('Usuarios')->truncate();
        
        // Crear usuario administrador (NO especificar ID_Usuario)
        $admin = User::create([
            'Nombre' => 'Administrador Principal',
            'Gmail' => 'admin@manheru.com',
            'Contrasena' => Hash::make('Admin123!'),
            'Telefono' => '555-123-4567',
            'Estatus' => 1, // Activo
            'ID_Rol' => 1, // Administrador
            'ID_Direccion' => null,
        ]);

        // Crear usuario normal
        $user = User::create([
            'Nombre' => 'Usuario Normal',
            'Gmail' => 'usuario@manheru.com',
            'Contrasena' => Hash::make('Usuario123!'),
            'Telefono' => '555-987-6543',
            'Estatus' => 1, // Activo
            'ID_Rol' => 2, // Usuario normal
            'ID_Direccion' => null,
        ]);

        $this->command->info("¡Usuarios creados exitosamente!");
        $this->command->info("Administrador (ID: {$admin->ID_Usuario}): admin@manheru.com / Contraseña: Admin123!");
        $this->command->info("Usuario (ID: {$user->ID_Usuario}): usuario@manheru.com / Contraseña: Usuario123!");
    }
}