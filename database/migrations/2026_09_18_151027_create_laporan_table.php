<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();

            $table->foreignId('magang_id')
                ->constrained('magang')
                ->cascadeOnDelete();

            $table->string('nama_file', 255);
            $table->string('file_path', 255);
            $table->timestamp('tanggal_upload')->nullable();

            $table->enum('status', [
                'belum_validasi',
                'disetujui',
                'ditolak'
            ])->default('belum_validasi');

            $table->text('catatan_dosen')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};