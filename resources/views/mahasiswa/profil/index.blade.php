@extends('layouts.app')
@section('title','Profil Mahasiswa')
@section('content')
<div class="space-y-6">
<div><h1 class="text-2xl font-bold text-slate-950">Profil Mahasiswa</h1><p class="mt-1 text-sm text-slate-500">Kelola data pribadi dan akun Anda.</p></div>
<div class="grid gap-6 lg:grid-cols-[1fr_380px]">
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
<h2 class="font-bold">Data Mahasiswa</h2>
<form method="POST" action="{{ route('mahasiswa.profil.update') }}" class="mt-5 space-y-4">@csrf @method('PATCH')
<div class="grid gap-4 sm:grid-cols-2">
<div><label class="text-sm font-semibold">Nama</label><input name="name" value="{{ old('name',$mahasiswa->user->name) }}" required class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@error('name')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror</div>
<div><label class="text-sm font-semibold">Email</label><input type="email" name="email" value="{{ old('email',$mahasiswa->user->email) }}" required class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@error('email')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror</div>
<div><label class="text-sm font-semibold">NIM</label><input name="nim" value="{{ old('nim',$mahasiswa->nim) }}" required class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@error('nim')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror</div>
<div><label class="text-sm font-semibold">Program Studi</label><input name="program_studi" value="{{ old('program_studi',$mahasiswa->program_studi) }}" required class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">@error('program_studi')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror</div>
<div><label class="text-sm font-semibold">No. HP</label><input name="no_hp" value="{{ old('no_hp',$mahasiswa->no_hp) }}" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm"></div>
<div><label class="text-sm font-semibold">Alamat</label><input name="alamat" value="{{ old('alamat',$mahasiswa->alamat) }}" class="mt-1.5 w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm"></div>
</div><button class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white">Simpan Profil</button>
</form></div>
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="font-bold">Ubah Password</h2>
<form method="POST" action="{{ route('mahasiswa.profil.password') }}" class="mt-5 space-y-4">@csrf @method('PATCH')
<input type="password" name="current_password" placeholder="Password saat ini" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
<input type="password" name="password" placeholder="Password baru" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
<input type="password" name="password_confirmation" placeholder="Konfirmasi password" required class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
@error('current_password')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
@error('password')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
<button class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Perbarui Password</button>
</form></div></div></div>
@endsection