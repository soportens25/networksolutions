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
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // id autoincremental
            $table->string('producto', 150); // Nombre del producto
            $table->text('descripcion')->nullable(); // Descripción del producto
            $table->integer('stock'); // Cantidad en inventario
            $table->decimal('precio', 20, 2); // Precio del producto
            $table->string('imagen')->nullable(); // Ruta o URL de la imagen
            $table->foreignId('id_categoria')->constrained('categorias'); // Llave foránea a categorias
            $table->foreignId('id_estado')->constrained('estados'); // Llave foránea a estado
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
        Schema::dropIfExists('productos');
    }
};
