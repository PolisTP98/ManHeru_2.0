<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Tipos', function (Blueprint $table) {
            $table->id('ID_Tipo');
            $table->string('Nombre', 50);
            $table->boolean('Estatus')->default(1);
            $table->timestamps();
        });

        // Insertar categorías por defecto
        DB::table('Tipos')->insert([
            ['Nombre' => 'Escritorios', 'Estatus' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'Sillas', 'Estatus' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'Archiveros', 'Estatus' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'Mesas', 'Estatus' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['Nombre' => 'Estanterías', 'Estatus' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('Tipos');
    }
};