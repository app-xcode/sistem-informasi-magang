@props([
    'column',
    'label',
])

@php
    $currentSort = request('sort');
    $currentDirection = request('direction', 'desc');
    $active = $currentSort === $column;
    $nextDirection = $active && $currentDirection === 'asc' ? 'desc' : 'asc';

    $params = request()->query();
    unset($params['page'], $params['export']);
    $params['sort'] = $column;
    $params['direction'] = $nextDirection;
@endphp

<a href="{{ request()->url().'?'.http_build_query($params) }}"
   class="inline-flex items-center gap-1.5 font-semibold text-white hover:text-slate-200"
   title="Urutkan {{ $label }}">
    <span>{{ $label }}</span>
    <i class="fa-solid {{ $active ? ($currentDirection === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down') : 'fa-sort' }} text-[10px] opacity-80" aria-hidden="true"></i>
</a>
