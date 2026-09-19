<?php

namespace App\Http\Requests\Dosen;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $dosen = $this->user()?->dosen;

        return [
            'nama' => ['required','string','max:150'],
            'nidn' => ['required','string','max:50', Rule::unique('dosen','nidn')->ignore($dosen?->id)],
            'no_hp' => ['nullable','string','max:30'],
            'alamat' => ['nullable','string'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($this->user()?->id)],
        ];
    }
}