@props(['title' => 'Dashboard'])

<header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
    <div class="flex min-h-20 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-3">
            <label for="sidebar-toggle" class="inline-flex h-10 w-10 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 lg:hidden" aria-label="Buka sidebar"><i class="fa-solid fa-bars" aria-hidden="true"></i></label>
            <div class="min-w-0">
                <h1 class="font-medium text-slate-600 truncate uppercase">Sistem Informasi Magang</h1>
            </div>
        </div>
        @php($notifications = app(\App\Services\NotificationService::class)->forUser(auth()->user()))
        <div class="relative flex items-center gap-2 sm:gap-3">
            <div class="group relative">
                <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50" aria-label="Notifikasi">
                    <i class="fa-solid fa-bell" aria-hidden="true"></i>
                    @if($notifications['total'] > 0)<span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold leading-5 text-white">{{ min($notifications['total'], 99) }}</span>@endif
                </button>
                <div class="absolute right-0 top-12 z-50 hidden w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3"><p class="text-sm font-bold text-slate-900">Notifikasi</p><span class="text-xs text-slate-500">{{ $notifications['total'] }} tindakan</span></div>
                    <div class="max-h-96 overflow-y-auto">
                        @forelse($notifications['items'] as $notification)
                            <a href="{{ $notification['href'] }}" class="flex gap-3 border-b border-slate-100 px-4 py-3 hover:bg-slate-50">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $notification['class'] }}"><i class="fa-solid {{ $notification['icon'] }}" aria-hidden="true"></i></span>
                                <span class="min-w-0"><span class="block text-sm font-semibold text-slate-800">{{ $notification['title'] }}</span><span class="mt-0.5 block text-xs leading-5 text-slate-500">{{ $notification['description'] }}</span></span>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center"><i class="fa-solid fa-circle-check text-2xl text-emerald-500"></i><p class="mt-2 text-sm font-semibold text-slate-700">Tidak ada tindakan</p><p class="mt-1 text-xs text-slate-500">Semua pekerjaan utama sudah diperiksa.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
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