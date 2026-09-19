<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm']) }} data-datatable>
    <div class="flex flex-col gap-3 border-b border-slate-200 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="datatable-title text-sm font-semibold text-slate-900">Data</div>
        <div class="flex flex-wrap items-center gap-2">
            <label class="relative">
                <span class="sr-only">Cari data</span>
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                <input type="search" data-table-search placeholder="Cari..." class="h-9 w-44 rounded-lg border border-slate-300 bg-white pl-8 pr-3 text-xs text-slate-700 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            </label>
            <button type="button" data-table-export data-no-icon class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-300 px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                <i class="fa-solid fa-file-csv" aria-hidden="true"></i>
                Export CSV
            </button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-sm">
            {{ $slot }}
        </table>
    </div>
</div>