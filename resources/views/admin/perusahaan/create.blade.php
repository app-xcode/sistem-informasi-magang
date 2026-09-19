@extends('layouts.app')

@section('title', 'Tambah Perusahaan')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.perusahaan.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">← Kembali ke Data Perusahaan</a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Tambah Perusahaan</h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan instansi atau perusahaan baru sebagai tempat magang.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('admin.perusahaan.store') }}">
                @include('admin.perusahaan._form', ['submitLabel' => 'Simpan Perusahaan'])
            </form>
        </div>
    </div>
@endsection