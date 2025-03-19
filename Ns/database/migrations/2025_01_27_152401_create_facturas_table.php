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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id('id'); // ID autoincremental
            $table->string('nombre_cliente'); // Nombre del cliente
            $table->string('direccion_cliente'); // Dirección del cliente
            $table->string('documento_cliente'); // Documento de identificación del cliente
            $table->string('telefono_cliente'); // Teléfono del cliente
            $table->string('correo_cliente'); // Correo electrónico del cliente
            $table->datetime('fecha_emision'); // Fecha de emisión de la factura
            $table->decimal('subtotal', 20, 2); // Subtotal de la factura
            $table->decimal('total', 20, 2); // Total de la factura
            $table->string('metodo_pago'); // Método de pago
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
        Schema::dropIfExists('facturas');
    }
};
