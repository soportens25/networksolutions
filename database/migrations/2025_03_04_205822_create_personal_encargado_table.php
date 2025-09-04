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
        Schema::create('personal_encargado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_equipo')->constrained('inventario')->onDelete('cascade');
            $table->string('usuario_responsable');
            $table->string('area_ubicacion');
            $table->date('fecha_asignacion');
            $table->date('fecha_devolucion')->nullable();
            $table->text('observacion')->nullable();
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
        Schema::dropIfExists('personal_encargado');
    }
};
