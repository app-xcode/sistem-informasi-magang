@props(['title' => null, 'value' => null, 'description' => null, 'icon' => null, 'iconClass' => 'bg-slate-100 text-slate-700'])

<section {{ $attributes->merge(['class' => 'group rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md']) }}>
    @if ($title || $value !== null || $description || $icon)
        <div class="flex items-start justify-between gap-4">
            <div class="min-w-0">
                @if ($title)<p class="text-sm font-medium text-slate-500">{{ $title }}</p>@endif
                @if ($value !== null)<p class="mt-2 text-2xl font-bold tracking-tight text-slate-950">{{ $value }}</p>@endif
                @if ($description)<p class="mt-1 text-xs text-slate-500">{{ $description }}</p>@endif
            </div>
            @if ($icon)
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $iconClass }}">
                    <i class="fa-solid {{ $icon }} text-lg" aria-hidden="true"></i>
                </div>
            @endif
        </div>
    @endif

    @if (trim($slot) !== '')
        <div @class(['mt-4' => $title || $value !== null || $description || $icon])>{{ $slot }}</div>
    @endif
</section>