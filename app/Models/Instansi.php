<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instansi extends Model
{
    protected $table = 'instansi';

    protected $fillable = [
        'nama_instansi',
        'alamat',
        'no_telp',
        'email',
        'penanggung_jawab',
    ];

    public function magang(): HasMany
    {
        return $this->hasMany(Magang::class);
    }
}