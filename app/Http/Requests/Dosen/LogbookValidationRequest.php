<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LogbookValidationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_validasi' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_dosen' => ['nullable', 'string', 'required_if:status_validasi,ditolak'],
        ];
    }

    public function attributes(): array
    {
        return [
            'status_validasi' => 'status validasi',
            'catatan_dosen' => 'catatan dosen',
        ];
    }
}
