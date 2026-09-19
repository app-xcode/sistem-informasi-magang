@extends('layouts.app')

@section('title', $pageTitle)

@section('content')
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="flex items-start gap-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-sm font-bold text-white">SM</div>
        <div>
            <h1 class="text-xl font-bold text-slate-950">{{ $pageTitle }}</h1>
            <p class="mt-1 text-sm leading-6 text-slate-500">{{ $pageDescription }}</p>
        </div>
    </div>
    <div class="mt-8 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
        <p class="text-sm font-medium text-slate-700">Halaman modul siap dikembangkan.</p>
        <p class="mt-1 text-xs text-slate-500">Fungsionalitas data akan ditambahkan pada tahap berikutnya.</p>
    </div>
</div>
@endsection
