@props(['title' => 'Dashboard'])

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex min-h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <label for="sidebar-toggle" class="inline-flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden" aria-label="Buka sidebar"><i class="fa-solid fa-bars" aria-hidden="true"></i></label>
            <div class="min-w-0">
                <h1 class="truncate text-xl font-bold tracking-tight text-slate-950 sm:text-2xl">Sistem Informasi Magang</h1>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                <p class="text-xs capitalize text-slate-500">{{ auth()->user()->role }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50 hover:text-slate-950"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>Keluar</button>
            </form>
        </div>
    </div>
</header>