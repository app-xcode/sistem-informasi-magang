<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MagangStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status_magang' => [
                'required',
                Rule::in(['belum_mulai', 'berlangsung', 'selesai']),
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'status_magang' => 'status magang',
        ];
    }
}
