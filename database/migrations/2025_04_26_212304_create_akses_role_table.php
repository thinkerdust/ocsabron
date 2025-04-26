<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akses_role', function (Blueprint $table) {
            $table->id(); // id INT(11) NOT NULL AUTO_INCREMENT
            $table->integer('id_role')->comment('id: role');
            $table->string('kode_menu', 5);
            $table->tinyInteger('flag_access')->default(0)->comment('0: readonly, 1: fullaccess, 9: no access');
            $table->timestamp('insert_at')->useCurrent()->nullable();
            $table->string('insert_by', 50)->nullable();
            $table->timestamp('update_at')->nullable();
            $table->string('update_by', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akses_role');
    }
};
