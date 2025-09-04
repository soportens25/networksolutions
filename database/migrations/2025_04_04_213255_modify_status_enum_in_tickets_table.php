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
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open', 'pending', 'in_progress', 'resolved', 'closed') DEFAULT 'pending'");
    }
    
    public function down()
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open'");
    }
};
