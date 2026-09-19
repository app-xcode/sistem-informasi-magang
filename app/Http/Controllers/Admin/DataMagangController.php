<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MagangStatusRequest;
use App\Models\Magang;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataMagangController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\Response
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        [$query, $sort, $direction, $perPage] = DataTable::sort(
            $this->query($search, $status), $request,
            ['mahasiswa' => fn ($q, $dir) => $q->orderBy(\App\Models\Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'), $dir), 'judul_magang' => 'judul_magang', 'dosen' => fn ($q, $dir) => $q->orderBy(\App\Models\Dosen::select('nama')->whereColumn('dosen.id', 'magang.dosen_id'), $dir), 'perusahaan' => fn ($q, $dir) => $q->orderBy(\App\Models\Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'), $dir), 'status_magang' => 'status_magang', 'created_at' => 'created_at'], 'created_at'
        );

        if ($request->string('export')->toString() === 'excel') {
            return ExcelXmlExporter::download('data-magang', ['Mahasiswa', 'NIM', 'Judul Magang', 'Dosen', 'Perusahaan', 'Status Magang'], $query->lazy(500)->map(
                fn ($item) => [$item->mahasiswa?->nama ?? '-', $item->mahasiswa?->nim ?? '-', $item->judul_magang ?: '-', $item->dosen?->nama ?? '-', $item->instansi?->nama_instansi ?? '-', $item->status_magang]
            ));
        }

        $magang = $query->paginate($perPage)->withQueryString();
        return view('admin.magang.index', compact('magang', 'search', 'status', 'sort', 'direction', 'perPage'));
    }

    private function query(?string $search, ?string $status)
    {
        return Magang::query()->with(['mahasiswa', 'dosen', 'instansi'])
            ->where('status_pengajuan', 'disetujui')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul_magang', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"))
                        ->orWhereHas('dosen', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('instansi', fn ($q) => $q->where('nama_instansi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status_magang', $status));
    }

    public function show(Magang $magang): View
    {
        $magang->load(['mahasiswa.user', 'dosen.user', 'instansi']);
        abort_unless($magang->status_pengajuan === 'disetujui', 404);
        return view('admin.magang.show', compact('magang'));
    }

    public function updateStatus(MagangStatusRequest $request, Magang $magang): RedirectResponse
    {
        abort_unless($magang->status_pengajuan === 'disetujui', 404);
        $magang->update(['status_magang' => $request->validated('status_magang')]);
        return redirect()->route('admin.magang.show', $magang)->with('success', 'Status magang berhasil diperbarui.');
    }
}