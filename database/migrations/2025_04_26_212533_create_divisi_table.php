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
        Schema::create('divisi', function (Blueprint $table) {
            $table->char('uid', 21)->primary(); // PRIMARY KEY uid
            $table->string('nama', 255);
            $table->tinyInteger('urutan')->default(1);
            $table->tinyInteger('status')->default(1)->comment('1: aktif');
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
        Schema::dropIfExists('divisi');
    }
};
