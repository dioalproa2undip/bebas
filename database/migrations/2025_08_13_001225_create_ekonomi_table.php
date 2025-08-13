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
        Schema::create('ekonomi', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('kategori');
            $table->integer('trisa');
            $table->integer('trida');
            $table->integer('tri');
            $table->integer('trifor');
            $table->integer('tahunan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekonomi');
    }
};
