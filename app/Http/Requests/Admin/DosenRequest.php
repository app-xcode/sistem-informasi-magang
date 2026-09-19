<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DosenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dosen = $this->route('dosen');

        return [
            'nidn' => [
                'required',
                'string',
                'max:30',
                Rule::unique('dosen', 'nidn')->ignore($dosen?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($dosen?->user_id),
            ],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'password' => [
                $dosen ? 'nullable' : 'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nidn' => 'NIDN',
            'nama' => 'nama',
            'email' => 'email',
            'no_hp' => 'nomor HP',
            'alamat' => 'alamat',
            'password' => 'password',
        ];
    }
}