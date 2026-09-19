@extends('layouts.app')
@section('title','Profil Dosen')
@section('content')
<div class="space-y-6">
<h1 class="text-2xl font-bold text-slate-950">Profil Dosen</h1>
<div class="grid gap-6 lg:grid-cols-2">
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="font-bold">Informasi Profil</h2>
<form method="POST" action="{{ route('dosen.profil.update') }}" class="mt-5 space-y-4">@csrf @method('PATCH')
<input name="nama" value="{{ old('nama',$dosen->nama) }}" placeholder="Nama" class="w-full rounded-lg border p-3 text-sm" required>
<input name="nidn" value="{{ old('nidn',$dosen->nidn) }}" placeholder="NIDN" class="w-full rounded-lg border p-3 text-sm" required>
<input name="email" type="email" value="{{ old('email',$dosen->user?->email ?? auth()->user()->email) }}" placeholder="Email" class="w-full rounded-lg border p-3 text-sm" required>
<input name="no_hp" value="{{ old('no_hp',$dosen->no_hp) }}" placeholder="No. HP" class="w-full rounded-lg border p-3 text-sm">
<textarea name="alamat" rows="4" placeholder="Alamat" class="w-full rounded-lg border p-3 text-sm">{{ old('alamat',$dosen->alamat) }}</textarea>
<button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Simpan Profil</button>
</form>
</div>
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="font-bold">Ganti Password</h2>
<form method="POST" action="{{ route('dosen.profil.password') }}" class="mt-5 space-y-4">@csrf @method('PATCH')
<input name="current_password" type="password" placeholder="Password saat ini" class="w-full rounded-lg border p-3 text-sm" required>
<input name="password" type="password" placeholder="Password baru" class="w-full rounded-lg border p-3 text-sm" required>
<input name="password_confirmation" type="password" placeholder="Konfirmasi password" class="w-full rounded-lg border p-3 text-sm" required>
<button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Ubah Password</button>
</form>
</div>
</div></div>
@endsection