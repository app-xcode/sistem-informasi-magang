@props([
    'type' => 'doughnut',
    'labels' => [],
    'data' => [],
    'title' => null,
    'description' => null,
])

@php
    $chartId = 'chart-'.str()->random(12);
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
    @if ($title)
        <div>
            <h2 class="font-semibold text-slate-900">{{ $title }}</h2>
            @if ($description)
                <p class="mt-1 text-xs text-slate-500">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="mt-4 h-64">
        <canvas id="{{ $chartId }}"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById(@json($chartId));
    if (!canvas || typeof Chart === 'undefined') return;

    new Chart(canvas, {
        type: @json($type),
        data: {
            labels: @json($labels),
            datasets: [{
                label: @json($title),
                data: @json($data),
                borderWidth: 2,
                borderColor: '#ffffff',
                backgroundColor: ['#0f172a', '#0ea5e9', '#10b981', '#f59e0b', '#f43f5e', '#64748b'],
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: @json($type !== 'bar'),
                    position: 'bottom',
                    labels: { usePointStyle: true, boxWidth: 8, padding: 16 },
                },
            },
        },
    });
});
</script>