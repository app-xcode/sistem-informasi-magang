<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $mahasiswa = auth()->user()->mahasiswa;
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255',Rule::unique('users','email')->ignore(auth()->id())],
            'nim' => ['required','string','max:50',Rule::unique('mahasiswa','nim')->ignore($mahasiswa?->id)],
            'program_studi' => ['required','string','max:150'],
            'no_hp' => ['nullable','string','max:30'],
            'alamat' => ['nullable','string','max:500'],
        ];
    }
}
