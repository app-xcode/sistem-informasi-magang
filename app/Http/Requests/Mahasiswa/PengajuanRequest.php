<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'instansi_id' => ['required','integer','exists:instansi,id'],
            'dosen_id' => ['nullable','integer','exists:dosen,id'],
            'judul_magang' => ['required','string','max:200'],
            'tanggal_mulai' => ['nullable','date','after_or_equal:today'],
            'tanggal_selesai' => ['nullable','date','after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable','string','max:5000'],
        ];
    }
}
