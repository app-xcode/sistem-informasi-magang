@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.dosen.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">← Kembali ke Data Dosen</a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Tambah Dosen</h1>
            <p class="mt-1 text-sm text-slate-500">Buat data dosen sekaligus akun untuk login ke sistem.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('admin.dosen.store') }}">
                @include('admin.dosen._form', ['submitLabel' => 'Simpan Dosen'])
            </form>
        </div>
    </div>
@endsection