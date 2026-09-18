<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('magang_id')
                ->unique()
                ->constrained('magang')
                ->cascadeOnDelete();

            $table->foreignId('dosen_id')
                ->constrained('dosen')
                ->restrictOnDelete();

            $table->decimal('kedisiplinan', 5, 2)->nullable();
            $table->decimal('tanggung_jawab', 5, 2)->nullable();
            $table->decimal('kerja_sama', 5, 2)->nullable();
            $table->decimal('kemampuan_teknis', 5, 2)->nullable();
            $table->decimal('sikap', 5, 2)->nullable();

            $table->decimal('nilai_akhir', 5, 2)->nullable();

            $table->text('catatan')->nullable();
            $table->date('tanggal_penilaian')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};