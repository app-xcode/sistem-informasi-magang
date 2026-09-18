<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_kegiatan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('magang_id')
                ->constrained('magang')
                ->cascadeOnDelete();

            $table->date('tanggal');
            $table->string('judul_kegiatan', 150);
            $table->text('deskripsi');
            $table->string('bukti_kegiatan', 255)->nullable();

            $table->enum('status_validasi', [
                'menunggu',
                'disetujui',
                'ditolak'
            ])->default('menunggu');

            $table->text('catatan_dosen')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_kegiatan');
    }
};