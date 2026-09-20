@extends('layouts.app')

@section('title', 'Tambah Instansi')

@section('content')
    <div class="mx-auto w-full max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('admin.instansi.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900">← Kembali ke Data Instansi</a>
            <h1 class="mt-3 text-2xl font-bold tracking-tight text-slate-950">Tambah Instansi</h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan instansi atau instansi baru sebagai tempat magang.</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('admin.instansi.store') }}">
                @include('admin.instansi._form', ['submitLabel' => 'Simpan Instansi'])
            </form>
        </div>
    </div>
@endsection