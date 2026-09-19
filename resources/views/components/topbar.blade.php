@props(['title' => 'Dashboard'])

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <label for="sidebar-toggle" class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden" aria-label="Buka sidebar">
                <span class="text-lg leading-none">&#9776;</span>
            </label>

            <div class="min-w-0">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Dashboard</p>
                <h1 class="truncate text-lg font-semibold text-slate-950 sm:text-xl">{{ $title }}</h1>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                Logout
            </button>
        </form>
    </div>
</header>
