<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id(); // id autoincremental
            $table->string('servicio', 100); // Nombre del servicio
            $table->string('tipo', 500); // Tipo de servicio
            $table->text('especificacion')->nullable(); // Especificaciones detalladas
            $table->string('imagen')->nullable(); // Ruta o URL de la imagen
            $table->string('imagen1')->nullable(); // Ruta o URL de la imagen
            $table->string('imagen2')->nullable(); // Ruta o URL de la imagen
            $table->string('imagen3')->nullable(); // Ruta o URL de la imagen

            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios');
    }
};
