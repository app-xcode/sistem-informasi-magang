@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-medium text-slate-500">Administrasi</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Pengaturan</h1>
        <p class="mt-1 text-sm text-slate-500">Kelola informasi akun administrator dan keamanan akses sistem.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Profil Administrator</h2>
                <p class="mt-1 text-xs text-slate-500">Perbarui nama dan email akun yang sedang digunakan.</p>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.profile') }}" class="space-y-5 p-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Simpan Profil
                </button>
            </form>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-slate-900">Keamanan Akun</h2>
                <p class="mt-1 text-xs text-slate-500">Ganti password administrator secara berkala.</p>
            </div>

            <form method="POST" action="{{ route('admin.pengaturan.password') }}" class="space-y-5 p-5">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Saat Ini</label>
                    <input id="current_password" type="password" name="current_password" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('current_password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Password Baru</label>
                    <input id="password" type="password" name="password" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                </div>

                <button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Ubah Password
                </button>
            </form>
        </section>
    </div>
</div>
@endsection
