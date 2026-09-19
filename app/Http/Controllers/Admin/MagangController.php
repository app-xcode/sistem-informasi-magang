<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MagangRequest;
use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Magang;
use App\Models\Mahasiswa;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MagangController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\Response
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();
        [$query, $sort, $direction, $perPage] = DataTable::sort(
            $this->query($search, $status), $request,
            ['mahasiswa' => fn ($q, $dir) => $q->orderBy(Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'), $dir), 'judul_magang' => 'judul_magang', 'dosen' => fn ($q, $dir) => $q->orderBy(Dosen::select('nama')->whereColumn('dosen.id', 'magang.dosen_id'), $dir), 'perusahaan' => fn ($q, $dir) => $q->orderBy(Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'), $dir), 'status_pengajuan' => 'status_pengajuan', 'created_at' => 'created_at'], 'created_at'
        );

        if ($request->string('export')->toString() === 'excel') {
            return ExcelXmlExporter::download('pengajuan-magang', ['Mahasiswa', 'NIM', 'Judul Magang', 'Dosen', 'Perusahaan', 'Status Pengajuan'], $query->cursor()->map(
                fn ($item) => [$item->mahasiswa?->nama ?? '-', $item->mahasiswa?->nim ?? '-', $item->judul_magang ?: '-', $item->dosen?->nama ?? '-', $item->instansi?->nama_instansi ?? '-', $item->status_pengajuan]
            ));
        }

        $magang = $query->paginate($perPage)->withQueryString();
        return view('admin.pengajuan.index', compact('magang', 'search', 'status', 'sort', 'direction', 'perPage'));
    }

    private function query(?string $search, ?string $status)
    {
        return Magang::query()->with(['mahasiswa', 'dosen', 'instansi'])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul_magang', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"))
                        ->orWhereHas('dosen', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('instansi', fn ($q) => $q->where('nama_instansi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status_pengajuan', $status));
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
        return redirect()->route('admin.pengajuan.index')->with('success', 'Pengajuan magang berhasil ditambahkan.');
    }
    public function edit(Magang $pengajuan): View
    {
        return view('admin.pengajuan.edit', ['pengajuan' => $pengajuan, 'mahasiswa' => Mahasiswa::orderBy('nama')->get(), 'dosen' => Dosen::orderBy('nama')->get(), 'instansi' => Instansi::orderBy('nama_instansi')->get()]);
    }
    public function update(MagangRequest $request, Magang $pengajuan): RedirectResponse
    {
        $pengajuan->update($request->validated());
        return redirect()->route('admin.pengajuan.index')->with('success', 'Pengajuan magang berhasil diperbarui.');
    }
    public function destroy(Magang $pengajuan): RedirectResponse
    {
        if ($pengajuan->logKegiatan()->exists() || $pengajuan->laporan()->exists() || $pengajuan->penilaian()->exists()) return back()->with('error', 'Pengajuan tidak dapat dihapus karena sudah memiliki data kegiatan, laporan, atau penilaian.');
        $pengajuan->delete();
        return redirect()->route('admin.pengajuan.index')->with('success', 'Pengajuan magang berhasil dihapus.');
    }
}