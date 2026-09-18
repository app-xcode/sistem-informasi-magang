<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogKegiatan extends Model
{
    protected $table = 'log_kegiatan';

    protected $fillable = [
        'magang_id',
        'tanggal',
        'judul_kegiatan',
        'deskripsi',
        'bukti_kegiatan',
        'status_validasi',
        'catatan_dosen',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function magang(): BelongsTo
    {
        return $this->belongsTo(Magang::class);
    }
}