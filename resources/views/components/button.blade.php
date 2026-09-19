@props(['href' => null, 'variant' => 'primary'])

@php
    $classes = $variant === 'secondary'
        ? 'border border-slate-200 bg-white text-slate-700 hover:bg-slate-50'
        : 'border border-teal-700 bg-teal-700 text-white hover:bg-teal-800';
@endphp

@if ($href)
    <a {{ $attributes->merge(['href' => $href, 'class' => 'inline-flex items-center justify-center rounded-md px-3 py-2 text-sm font-medium shadow-sm transition '.$classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-md px-3 py-2 text-sm font-medium shadow-sm transition '.$classes]) }}>
        {{ $slot }}
    </button>
@endif
