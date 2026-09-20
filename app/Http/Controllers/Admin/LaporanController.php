<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\Response
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        [$query, $sort, $direction, $perPage] = DataTable::sort(
            $this->query($search, $status), $request,
            ['mahasiswa' => fn ($q, $dir) => $q->orderBy(\App\Models\Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'), $dir), 'instansi' => fn ($q, $dir) => $q->orderBy(\App\Models\Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'), $dir), 'dosen' => fn ($q, $dir) => $q->orderBy(\App\Models\Dosen::select('nama')->whereColumn('dosen.id', 'magang.dosen_id'), $dir), 'nama_file' => 'nama_file', 'tanggal_upload' => 'tanggal_upload', 'status' => 'status'], 'tanggal_upload'
        );

        if ($request->string('export')->toString() === 'excel') {
            return ExcelXmlExporter::download('laporan-magang', ['Mahasiswa', 'NIM', 'Instansi', 'Dosen', 'File Laporan', 'Tanggal Upload', 'Status'], $query->lazy(500)->map(
                fn ($item) => [$item->magang?->mahasiswa?->nama ?? '-', $item->magang?->mahasiswa?->nim ?? '-', $item->magang?->instansi?->nama_instansi ?? '-', $item->magang?->dosen?->nama ?? '-', $item->nama_file, $item->tanggal_upload?->format('d M Y H:i') ?? '-', $item->status]
            ));
        }

        $laporan = $query->paginate($perPage)->withQueryString();
        return view('admin.laporan.index', compact('laporan', 'search', 'status', 'sort', 'direction', 'perPage'));
    }

    private function query(?string $search, ?string $status)
    {
        return Laporan::query()->with(['magang.mahasiswa', 'magang.dosen', 'magang.instansi'])
            ->whereHas('magang', fn ($query) => $query->where('status_pengajuan', 'disetujui'))
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama_file', 'like', "%{$search}%")
                        ->orWhereHas('magang.mahasiswa', fn ($q) => $q->where('nama', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"))
                        ->orWhereHas('magang.dosen', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('magang.instansi', fn ($q) => $q->where('nama_instansi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status', $status));
    }

    public function download(Laporan $laporan)
    {
        abort_unless($laporan->magang?->status_pengajuan === 'disetujui', 404);
        abort_unless(Storage::disk('public')->exists($laporan->file_path), 404);

        return Storage::disk('public')->download($laporan->file_path, $laporan->nama_file);
    }
}