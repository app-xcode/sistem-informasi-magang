<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MagangRequest;
use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Magang;
use App\Models\Mahasiswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MagangController extends Controller
{
    public function index(): View
    {
        $search = request('q');
        $status = request('status');

        $magang = Magang::query()
            ->with(['mahasiswa', 'dosen', 'instansi'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul_magang', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', fn ($q) => $q
                            ->where('nama', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%"))
                        ->orWhereHas('dosen', fn ($q) => $q
                            ->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('instansi', fn ($q) => $q
                            ->where('nama_instansi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status_pengajuan', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengajuan.index', compact('magang', 'search', 'status'));
    }

    public function create(): View
    {
        return view('admin.pengajuan.create', [
            'mahasiswa' => Mahasiswa::orderBy('nama')->get(),
            'dosen' => Dosen::orderBy('nama')->get(),
            'instansi' => Instansi::orderBy('nama_instansi')->get(),
        ]);
    }

    public function store(MagangRequest $request): RedirectResponse
    {
        Magang::create($request->validated());

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan magang berhasil ditambahkan.');
    }

    public function edit(Magang $pengajuan): View
    {
        return view('admin.pengajuan.edit', [
            'pengajuan' => $pengajuan,
            'mahasiswa' => Mahasiswa::orderBy('nama')->get(),
            'dosen' => Dosen::orderBy('nama')->get(),
            'instansi' => Instansi::orderBy('nama_instansi')->get(),
        ]);
    }

    public function update(MagangRequest $request, Magang $pengajuan): RedirectResponse
    {
        $pengajuan->update($request->validated());

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan magang berhasil diperbarui.');
    }

    public function destroy(Magang $pengajuan): RedirectResponse
    {
        if ($pengajuan->logKegiatan()->exists() || $pengajuan->laporan()->exists() || $pengajuan->penilaian()->exists()) {
            return back()->with('error', 'Pengajuan tidak dapat dihapus karena sudah memiliki data kegiatan, laporan, atau penilaian.');
        }

        $pengajuan->delete();

        return redirect()
            ->route('admin.pengajuan.index')
            ->with('success', 'Pengajuan magang berhasil dihapus.');
    }
}