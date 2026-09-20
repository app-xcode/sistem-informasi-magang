<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class LogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal' => ['required', 'date'],
            'judul_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'bukti_kegiatan' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                function ($attribute, $value, $fail) {
                    $maxKb = str_starts_with((string) $value->getMimeType(), 'image/')
                        ? 1024
                        : 3072;

                    if ($value->getSize() > ($maxKb * 1024)) {
                        $limit = $maxKb === 1024 ? '1 MB' : '3 MB';
                        $fail("Ukuran {$attribute} maksimal {$limit}.");
                    }
                },
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal' => 'tanggal kegiatan',
            'judul_kegiatan' => 'judul kegiatan',
            'deskripsi' => 'deskripsi',
            'bukti_kegiatan' => 'bukti kegiatan',
        ];
    }
}
