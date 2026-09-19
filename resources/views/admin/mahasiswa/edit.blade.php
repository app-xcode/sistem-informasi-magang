@extends('layouts.app')

@section('title', 'Edit Mahasiswa')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.mahasiswa.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">← Kembali ke Data Mahasiswa</a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Edit Mahasiswa</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui informasi mahasiswa dan akun login.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa) }}">
                @method('PUT')
                @include('admin.mahasiswa._form', ['submitLabel' => 'Simpan Perubahan'])
            </form>
        </div>
    </div>
@endsection
