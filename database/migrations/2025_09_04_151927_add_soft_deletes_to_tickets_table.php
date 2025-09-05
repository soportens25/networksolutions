<?php
// Crear la migración
// php artisan make:migration add_soft_deletes_to_tickets_table --table=tickets

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToTicketsTable extends Migration
{
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->softDeletes(); // Esto agrega la columna deleted_at
        });
    }

    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
