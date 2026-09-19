@php
    $role = auth()->user()->role ?? null;

    $menus = [
        'admin' => [
            ['label' => 'Dashboard', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => 'Data Mahasiswa', 'href' => '#', 'active' => false],
            ['label' => 'Data Dosen', 'href' => '#', 'active' => false],
            ['label' => 'Data Perusahaan', 'href' => '#', 'active' => false],
            ['label' => 'Pengajuan Magang', 'href' => '#', 'active' => false],
            ['label' => 'Data Magang', 'href' => '#', 'active' => false],
            ['label' => 'Monitoring Magang', 'href' => '#', 'active' => false],
            ['label' => 'Laporan', 'href' => '#', 'active' => false],
            ['label' => 'Pengaturan', 'href' => '#', 'active' => false],
        ],
        'mahasiswa' => [
            ['label' => 'Dashboard', 'href' => route('mahasiswa.dashboard'), 'active' => request()->routeIs('mahasiswa.dashboard')],
            ['label' => 'Profil', 'href' => '#', 'active' => false],
            ['label' => 'Pengajuan Magang', 'href' => '#', 'active' => false],
            ['label' => 'Tempat Magang', 'href' => '#', 'active' => false],
            ['label' => 'Status Magang', 'href' => '#', 'active' => false],
            ['label' => 'Logbook', 'href' => '#', 'active' => false],
            ['label' => 'Laporan Magang', 'href' => '#', 'active' => false],
        ],
        'dosen' => [
            ['label' => 'Dashboard', 'href' => route('dosen.dashboard'), 'active' => request()->routeIs('dosen.dashboard')],
            ['label' => 'Profil', 'href' => '#', 'active' => false],
            ['label' => 'Mahasiswa Bimbingan', 'href' => '#', 'active' => false],
            ['label' => 'Pengajuan Magang', 'href' => '#', 'active' => false],
            ['label' => 'Monitoring Magang', 'href' => '#', 'active' => false],
            ['label' => 'Logbook', 'href' => '#', 'active' => false],
            ['label' => 'Penilaian', 'href' => '#', 'active' => false],
            ['label' => 'Laporan', 'href' => '#', 'active' => false],
        ],
    ];
@endphp

<aside class="fixed inset-y-0 left-0 z-40 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 peer-checked:translate-x-0 lg:sticky lg:translate-x-0">
    <div class="flex h-16 items-center justify-between border-b border-slate-200 px-5">
        <a href="{{ $role ? route($role.'.dashboard') : '#' }}" class="min-w-0">
            <span class="block text-sm font-semibold uppercase tracking-wide text-teal-700">SIM Magang</span>
            <span class="block truncate text-xs text-slate-500">Sistem Informasi Magang</span>
        </a>

        <label for="sidebar-toggle" class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Tutup sidebar">
            <span class="text-xl leading-none">&times;</span>
        </label>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <div class="flex flex-col gap-1">
            @foreach ($menus[$role] ?? [] as $menu)
                <a
                    href="{{ $menu['href'] }}"
                    class="rounded-md px-3 py-2 text-sm font-medium transition {{ $menu['active'] ? 'bg-teal-50 text-teal-800 ring-1 ring-teal-100' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950' }}"
                >
                    {{ $menu['label'] }}
                </a>
            @endforeach
        </div>
    </nav>

    <div class="border-t border-slate-200 p-4">
        <div class="rounded-md bg-slate-50 p-3">
            <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
            <p class="text-xs capitalize text-slate-500">{{ $role }}</p>
        </div>
    </div>
</aside>
