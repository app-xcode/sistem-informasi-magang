<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class InstansiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_instansi' => ['required', 'string', 'max:150'],
            'alamat' => ['required', 'string'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'penanggung_jawab' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_instansi' => 'nama instansi',
            'alamat' => 'alamat',
            'no_telp' => 'nomor telepon',
            'email' => 'email',
            'penanggung_jawab' => 'penanggung jawab',
        ];
    }
}