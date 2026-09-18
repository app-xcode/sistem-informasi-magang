<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('magang', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswa')
                ->cascadeOnDelete();

            $table->foreignId('dosen_id')
                ->nullable()
                ->constrained('dosen')
                ->nullOnDelete();

            $table->foreignId('instansi_id')
                ->constrained('instansi')
                ->restrictOnDelete();

            $table->string('judul_magang', 200)->nullable();

            $table->date('tanggal_pengajuan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();

            $table->enum('status_pengajuan', [
                'diajukan',
                'disetujui',
                'ditolak'
            ])->default('diajukan');

            $table->enum('status_magang', [
                'belum_mulai',
                'berlangsung',
                'selesai'
            ])->default('belum_mulai');

            $table->text('keterangan')->nullable();
            $table->text('alasan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('magang');
    }
};