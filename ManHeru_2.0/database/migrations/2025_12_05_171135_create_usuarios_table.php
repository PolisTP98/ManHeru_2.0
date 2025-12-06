<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Usuarios', function (Blueprint $table) {
            $table->id('ID_Usuario');
            $table->string('Nombre', 100);
            $table->string('Gmail', 100)->unique();
            $table->string('Contrasena', 255);
            $table->string('Telefono', 20);
            $table->tinyInteger('Estatus')->default(1); // 1 = Activo, 0 = Inactivo
            $table->unsignedBigInteger('ID_Rol')->default(2); // 2 = Usuario normal
            $table->unsignedBigInteger('ID_Direccion')->nullable();
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuarios');
    }
};