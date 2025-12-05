<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Productos', function (Blueprint $table) {
            $table->id('ID_Producto');
            $table->string('Nombre', 100);
            $table->text('Descripcion')->nullable();
            $table->decimal('Precio', 10, 2)->default(0);
            $table->string('Imagen', 255)->nullable();
            $table->integer('Stock')->default(0);
            $table->boolean('Estatus')->default(1);
            $table->unsignedBigInteger('ID_Tipo');
            $table->timestamps();

            $table->foreign('ID_Tipo')
                  ->references('ID_Tipo')
                  ->on('Tipos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Productos');
    }
};