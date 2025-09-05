<?php
// database/migrations/xxxx_update_status_enum_in_tickets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInTicketsTable extends Migration
{
    public function up()
    {
        // Agregar 'assigned' al ENUM existente
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('pending', 'assigned', 'open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        // Rollback - remover 'assigned' del ENUM
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('pending', 'open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }
}
