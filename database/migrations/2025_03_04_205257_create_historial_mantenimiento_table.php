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
        Schema::create('historial_mantenimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_equipo')->constrained('inventario')->onDelete('cascade');
            $table->string('encargado');
            $table->string('tipo_mantenimiento');
            $table->date('fecha_mantenimiento');
            $table->text('descripcion');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_mantenimiento');
    }
};
