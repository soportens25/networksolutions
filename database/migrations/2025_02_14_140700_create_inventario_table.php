<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre_equipo', 255);
            $table->string('sticker', 100);
            $table->string('marca_equipo', 100);
            $table->string('tipo_equipo', 100);
            $table->string('sistema_operativo', 100);
            $table->string('numero_serial', 100)->unique();
            $table->string('idioma', 50);
            $table->string('procesador', 100);
            $table->string('velocidad_procesador', 50);
            $table->string('tipo_conexion', 50);
            $table->string('ip', 50)->nullable();
            $table->string('mac', 50)->nullable();
            $table->string('memoria_ram', 50);
            $table->integer('cantidad_memoria');
            $table->integer('slots_memoria');
            $table->integer('frecuencia_memoria');
            $table->string('version_bios', 100)->nullable();
            $table->integer('cantidad_discos');
            $table->string('tipo_discos', 100);
            $table->string('espacio_discos', 100);
            $table->string('grafica', 100)->nullable();
            $table->text('licencias')->nullable();
            $table->text('perifericos')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventario');
    }
};
