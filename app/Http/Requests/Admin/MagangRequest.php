<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MagangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id' => ['required', 'integer', 'exists:mahasiswa,id'],
            'dosen_id' => ['required', 'integer', 'exists:dosen,id'],
            'instansi_id' => ['required', 'integer', 'exists:instansi,id'],
            'judul_magang' => ['required', 'string', 'max:255'],
            'tanggal_pengajuan' => ['required', 'date'],
            'tanggal_mulai' => ['nullable', 'date', 'after_or_equal:tanggal_pengajuan'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'status_pengajuan' => ['required', Rule::in(['diajukan', 'disetujui', 'ditolak'])],
            'keterangan' => ['nullable', 'string'],
            'alasan_penolakan' => [
                'nullable',
                'string',
                'required_if:status_pengajuan,ditolak',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'mahasiswa_id' => 'mahasiswa',
            'dosen_id' => 'dosen pembimbing',
            'instansi_id' => 'instansi',
            'judul_magang' => 'judul magang',
            'tanggal_pengajuan' => 'tanggal pengajuan',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_selesai' => 'tanggal selesai',
            'status_pengajuan' => 'status pengajuan',
            'keterangan' => 'keterangan',
            'alasan_penolakan' => 'alasan penolakan',
        ];
    }
}