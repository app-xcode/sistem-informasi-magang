@php
    $role = auth()->user()->role ?? null;
    $menus = [
        'admin' => [
            ['label' => 'Dashboard', 'icon' => 'fa-house', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => 'Data Mahasiswa', 'icon' => 'fa-users', 'href' => route('admin.mahasiswa.index'), 'active' => request()->routeIs('admin.mahasiswa.*')],
            ['label' => 'Data Dosen', 'icon' => 'fa-user-tie', 'href' => route('admin.dosen.index'), 'active' => request()->routeIs('admin.dosen.*')],
            ['label' => 'Data Instansi', 'icon' => 'fa-building', 'href' => route('admin.instansi.index'), 'active' => request()->routeIs('admin.instansi.*')],
            ['label' => 'Pengajuan Magang', 'icon' => 'fa-file-circle-plus', 'href' => route('admin.pengajuan.index'), 'active' => request()->routeIs('admin.pengajuan.*')],
            ['label' => 'Data Magang', 'icon' => 'fa-briefcase', 'href' => route('admin.magang.index'), 'active' => request()->routeIs('admin.magang.*')],
            ['label' => 'Monitoring Magang', 'icon' => 'fa-chart-line', 'href' => route('admin.monitoring.index'), 'active' => request()->routeIs('admin.monitoring.*')],
            ['label' => 'Laporan', 'icon' => 'fa-file-lines', 'href' => route('admin.laporan.index'), 'active' => request()->routeIs('admin.laporan.*')],
            ['label' => 'Pengaturan', 'icon' => 'fa-gear', 'href' => route('admin.pengaturan.index'), 'active' => request()->routeIs('admin.pengaturan.*')],
        ],
        'mahasiswa' => [
            ['label' => 'Dashboard', 'icon' => 'fa-house', 'href' => route('mahasiswa.dashboard'), 'active' => request()->routeIs('mahasiswa.dashboard')],
            ['label' => 'Profil', 'icon' => 'fa-user', 'href' => route('mahasiswa.profil'), 'active' => request()->routeIs('mahasiswa.profil')],
            ['label' => 'Pengajuan Magang', 'icon' => 'fa-file-circle-plus', 'href' => route('mahasiswa.pengajuan'), 'active' => request()->routeIs('mahasiswa.pengajuan')],
            ['label' => 'Tempat Magang', 'icon' => 'fa-building', 'href' => route('mahasiswa.tempat-magang'), 'active' => request()->routeIs('mahasiswa.tempat-magang')],
            ['label' => 'Status Magang', 'icon' => 'fa-clipboard-check', 'href' => route('mahasiswa.status-magang'), 'active' => request()->routeIs('mahasiswa.status-magang')],
            ['label' => 'Logbook', 'icon' => 'fa-book-open', 'href' => route('mahasiswa.logbook'), 'active' => request()->routeIs('mahasiswa.logbook')],
            ['label' => 'Laporan Magang', 'icon' => 'fa-file-lines', 'href' => route('mahasiswa.laporan'), 'active' => request()->routeIs('mahasiswa.laporan')],
        ],
        'dosen' => [
            ['label' => 'Dashboard', 'icon' => 'fa-house', 'href' => route('dosen.dashboard'), 'active' => request()->routeIs('dosen.dashboard')],
            ['label' => 'Profil', 'icon' => 'fa-user', 'href' => route('dosen.profil'), 'active' => request()->routeIs('dosen.profil')],
            ['label' => 'Mahasiswa Bimbingan', 'icon' => 'fa-user-group', 'href' => route('dosen.mahasiswa-bimbingan'), 'active' => request()->routeIs('dosen.mahasiswa-bimbingan')],
            ['label' => 'Pengajuan Magang', 'icon' => 'fa-file-circle-plus', 'href' => route('dosen.pengajuan'), 'active' => request()->routeIs('dosen.pengajuan')],
            ['label' => 'Monitoring Magang', 'icon' => 'fa-chart-line', 'href' => route('dosen.monitoring'), 'active' => request()->routeIs('dosen.monitoring')],
            ['label' => 'Logbook', 'icon' => 'fa-book-open', 'href' => route('dosen.logbook'), 'active' => request()->routeIs('dosen.logbook')],
            ['label' => 'Penilaian', 'icon' => 'fa-star', 'href' => route('dosen.penilaian'), 'active' => request()->routeIs('dosen.penilaian')],
            ['label' => 'Laporan', 'icon' => 'fa-file-lines', 'href' => route('dosen.laporan'), 'active' => request()->routeIs('dosen.laporan')],
        ],
    ];
    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'mahasiswa' => 'Mahasiswa',
        'dosen' => 'Dosen',
        default => 'Pengguna',
    };
@endphp

<aside class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 peer-checked:translate-x-0 lg:sticky lg:translate-x-0">
    <div class="flex h-20 items-center gap-3 border-b border-slate-200 px-5">
        <a href="{{ $role ? route($role.'.dashboard') : '#' }}" class="flex min-w-0 items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white shadow-sm"><i class="fa-solid fa-book-bookmark" aria-hidden="true"></i></span>
            <span class="min-w-0">
                <span class="block truncate text-sm font-bold text-slate-950">SIM Magang</span>
                <span class="block truncate text-xs text-slate-500">Sistem Informasi Magang</span>
            </span>
        </a>
        <label for="sidebar-toggle" class="ml-auto inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Tutup sidebar"><i class="fa-solid fa-xmark" aria-hidden="true"></i></label>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-5">
        <p class="mb-3 px-3 text-[11px] font-semibold uppercase tracking-wider text-slate-400"><i class="fa-solid fa-bars-staggered mr-2" aria-hidden="true"></i>Menu Utama</p>
        <div class="flex flex-col gap-1">
            @foreach ($menus[$role] ?? [] as $menu)
                <a href="{{ $menu['href'] }}" class="group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $menu['active'] ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}">
                    <i class="fa-solid {{ $menu['icon'] }} mr-3 w-4 text-center" aria-hidden="true"></i>
                    {{ $menu['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    <div class="border-t border-slate-200 p-4">
        <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-slate-200 text-sm font-semibold text-slate-700"><i class="fa-solid fa-user" aria-hidden="true"></i></div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500">{{ $roleLabel }}</p>
            </div>
        </div>
    </div>
</aside>