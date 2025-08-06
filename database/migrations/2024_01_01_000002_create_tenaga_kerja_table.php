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
        Schema::create('tenaga_kerja', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->string('kecamatan');
            $table->integer('jumlah_angkatan_kerja');
            $table->integer('jumlah_bekerja');
            $table->integer('jumlah_pengangguran');
            $table->decimal('tingkat_pengangguran', 5, 2);
            $table->decimal('tingkat_partisipasi_kerja', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenaga_kerja');
    }
}; 