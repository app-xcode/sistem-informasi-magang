@if (session('success') || session('error') || $errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-md border px-4 py-3 text-sm '.(session('error') || $errors->any() ? 'border-rose-200 bg-rose-50 text-rose-800' : 'border-emerald-200 bg-emerald-50 text-emerald-800')]) }}>
        @if (session('success'))
            {{ session('success') }}
        @elseif (session('error'))
            {{ session('error') }}
        @else
            {{ $errors->first() }}
        @endif
    </div>
@endif
