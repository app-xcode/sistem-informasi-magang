<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaporanValidationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(['disetujui','ditolak'])],
            'catatan_dosen' => ['nullable','string','required_if:status,ditolak'],
        ];
    }
}