<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Magang extends Model
{
    protected $table = 'magang';

    protected $fillable = [
        'mahasiswa_id',
        'dosen_id',
        'instansi_id',
        'judul_magang',
        'tanggal_pengajuan',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pengajuan',
        'status_magang',
        'keterangan',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    public function instansi(): BelongsTo
    {
        return $this->belongsTo(Instansi::class);
    }

    public function logKegiatan(): HasMany
    {
        return $this->hasMany(LogKegiatan::class);
    }

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }

    public function penilaian(): HasOne
    {
        return $this->hasOne(Penilaian::class);
    }
}