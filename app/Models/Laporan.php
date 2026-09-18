<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'magang_id',
        'nama_file',
        'file_path',
        'tanggal_upload',
        'status',
        'catatan_dosen',
    ];

    protected $casts = [
        'tanggal_upload' => 'datetime',
    ];

    public function magang(): BelongsTo
    {
        return $this->belongsTo(Magang::class);
    }
}