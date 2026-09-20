@props(['href' => null, 'variant' => 'primary'])

@php
    $classes = $variant === 'secondary'
        ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
        : 'border border-slate-900 bg-slate-900 text-white hover:bg-slate-800';

    $label = trim(strip_tags((string) $slot));
    $isAdd = preg_match('/^\+?\s*Tambah\b/i', $label);
@endphp

@if ($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => 'inline-flex items-center justify-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium shadow-sm transition '.$classes]) }}>
        @if($isAdd)<i class="fa-solid fa-plus w-4 text-center" aria-hidden="true"></i>@endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-1.5 rounded-md px-3 py-2 text-sm font-medium shadow-sm transition '.$classes]) }}>
        @if($isAdd)<i class="fa-solid fa-plus w-4 text-center" aria-hidden="true"></i>@endif
        {{ $slot }}
    </button>
@endif
