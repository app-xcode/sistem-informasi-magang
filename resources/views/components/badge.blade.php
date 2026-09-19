@props(['status' => 'default'])

@php
    $classes = match ($status) {
        'disetujui', 'selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'ditolak' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'berlangsung' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'diajukan', 'menunggu', 'belum_validasi' => 'bg-amber-50 text-amber-700 ring-amber-200',
        default => 'bg-slate-100 text-slate-700 ring-slate-200',
    };

    $labels = [
        'diajukan' => 'Menunggu',
        'menunggu' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'belum_mulai' => 'Belum Mulai',
        'berlangsung' => 'Berlangsung',
        'selesai' => 'Selesai',
        'belum_validasi' => 'Belum Validasi',
    ];

    $label = $labels[$status] ?? str_replace('_', ' ', $status);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset '.$classes]) }}>
    {{ $slot->isEmpty() ? $label : $slot }}
</span>