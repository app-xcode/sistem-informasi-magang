@extends('layouts.app')
@section('title','Penilaian')
@section('content')
<div class="space-y-6">
    <div><h1 class="text-2xl font-bold tracking-tight text-slate-950">Penilaian Magang</h1><p class="mt-1 text-sm text-slate-500">Kelola penilaian mahasiswa bimbingan.</p></div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200 text-sm"><thead class="bg-slate-50"><tr><th class="px-5 py-3 text-left font-semibold text-slate-600">Mahasiswa</th><th class="px-5 py-3 text-left font-semibold text-slate-600">Perusahaan</th><th class="px-5 py-3 text-center font-semibold text-slate-600">Nilai Akhir</th><th class="px-5 py-3 text-left font-semibold text-slate-600">Tanggal</th><th class="px-5 py-3 text-right font-semibold text-slate-600">Aksi</th></tr></thead><tbody class="divide-y divide-slate-100">
        @forelse($magang as $item)<tr class="align-middle"><td class="px-5 py-4"><p class="font-semibold text-slate-900">{{ $item->mahasiswa?->nama }}</p><p class="text-xs text-slate-500">{{ $item->mahasiswa?->nim }}</p></td><td class="px-5 py-4 text-slate-600">{{ $item->instansi?->nama_instansi }}</td><td class="px-5 py-4 text-center font-semibold text-slate-900">{{ $item->penilaian?->nilai_akhir ?? 'Belum dinilai' }}</td><td class="px-5 py-4 text-slate-600">{{ $item->penilaian?->tanggal_penilaian?->format('d M Y') ?? '-' }}</td><td class="px-5 py-4 text-right"><a href="{{ route('dosen.penilaian.edit',$item) }}" class="inline-flex rounded-lg bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white hover:bg-slate-800">{{ $item->penilaian ? 'Edit Nilai' : 'Beri Nilai' }}</a></td></tr>
        @empty<tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada mahasiswa untuk dinilai.</td></tr>@endforelse
        </tbody></table></div>
    </div>
    @if($magang->hasPages())<div>{{ $magang->links() }}</div>@endif
</div>
@endsection