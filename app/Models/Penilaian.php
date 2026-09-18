<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'magang_id',
        'dosen_id',
        'kedisiplinan',
        'tanggung_jawab',
        'kerja_sama',
        'kemampuan_teknis',
        'sikap',
        'nilai_akhir',
        'catatan',
        'tanggal_penilaian',
    ];

    protected $casts = [
        'kedisiplinan' => 'decimal:2',
        'tanggung_jawab' => 'decimal:2',
        'kerja_sama' => 'decimal:2',
        'kemampuan_teknis' => 'decimal:2',
        'sikap' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'tanggal_penilaian' => 'date',
    ];

    public function magang(): BelongsTo
    {
        return $this->belongsTo(Magang::class);
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}