@extends('layouts.app')

@section('title', 'Tambah Pengajuan Magang')

@section('content')
<div class="mx-auto w-full max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('admin.pengajuan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">← Kembali ke Pengajuan Magang</a>
        <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Tambah Pengajuan Magang</h1>
        <p class="mt-1 text-sm text-slate-500">Hubungkan mahasiswa dengan dosen pembimbing dan instansi tempat magang.</p>
    </div>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <form method="POST" action="{{ route('admin.pengajuan.store') }}">
            @include('admin.pengajuan._form', ['submitLabel' => 'Simpan Pengajuan'])
        </form>
    </div>
</div>
@endsection