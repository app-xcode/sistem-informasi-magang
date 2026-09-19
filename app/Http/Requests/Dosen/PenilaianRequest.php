<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;

class PenilaianRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'kedisiplinan' => ['required','numeric','min:0','max:100'],
            'tanggung_jawab' => ['required','numeric','min:0','max:100'],
            'kerja_sama' => ['required','numeric','min:0','max:100'],
            'kemampuan_teknis' => ['required','numeric','min:0','max:100'],
            'sikap' => ['required','numeric','min:0','max:100'],
            'catatan' => ['nullable','string'],
        ];
    }
}