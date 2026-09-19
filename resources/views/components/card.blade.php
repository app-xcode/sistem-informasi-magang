@props(['title' => null, 'value' => null, 'description' => null])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white p-5 shadow-sm']) }}>
    @if ($title || $value !== null || $description)
        <div class="flex flex-col gap-1">
            @if ($title)<p class="text-sm font-medium text-slate-500">{{ $title }}</p>@endif
            @if ($value !== null)<p class="text-2xl font-bold tracking-tight text-slate-950">{{ $value }}</p>@endif
            @if ($description)<p class="text-sm text-slate-500">{{ $description }}</p>@endif
        </div>
    @endif
    @if (trim($slot) !== '')
        <div @class(['mt-4' => $title || $value !== null || $description])>{{ $slot }}</div>
    @endif
</section>