<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar primero el seeder de usuarios
        $this->call([
            UsuariosSeeder::class,
        ]);
        
        // Puedes agregar otros seeders aquí
        // $this->call([RolesSeeder::class]);
    }
}