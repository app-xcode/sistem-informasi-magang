@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Manajemen Data</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Data Mahasiswa</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola akun dan informasi mahasiswa yang terdaftar.</p>
            </div>
            <x-button href="{{ route('admin.mahasiswa.create') }}">+ Tambah Mahasiswa</x-button>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.mahasiswa.index') }}" class="flex flex-col gap-3 sm:flex-row">
                <div class="flex-1">
                    <label for="q" class="sr-only">Cari mahasiswa</label>
                    <input id="q" name="q" value="{{ $search }}" type="search"
                        placeholder="Cari NIM, nama, program studi, atau email..."
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                </div>
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">Cari</button>
                @if ($search)
                    <a href="{{ route('admin.mahasiswa.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Mahasiswa</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">NIM</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Program Studi</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">No. HP</th>
                            <th class="px-5 py-3 text-left font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($mahasiswa as $item)
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-4">
                                    <div class="font-semibold text-slate-900">{{ $item->nama }}</div>
                                    <div class="mt-0.5 text-xs text-slate-500">{{ $item->user?->email ?? '-' }}</div>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-700">{{ $item->nim }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $item->program_studi }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $item->no_hp ?: '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.mahasiswa.edit', $item) }}" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">Edit</a>
                                        <form method="POST" action="{{ route('admin.mahasiswa.destroy', $item) }}" onsubmit="return confirm('Hapus data mahasiswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-3 py-1.5 text-xs font-semibold text-rose-700 hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($mahasiswa->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $mahasiswa->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
