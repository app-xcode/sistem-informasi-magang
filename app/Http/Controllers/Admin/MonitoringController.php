<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\Response
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        [$query, $sort, $direction, $perPage] = DataTable::sort(
            $this->query($search, $status), $request,
            ['mahasiswa' => fn ($q, $dir) => $q->orderBy(\App\Models\Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'), $dir), 'dosen' => fn ($q, $dir) => $q->orderBy(\App\Models\Dosen::select('nama')->whereColumn('dosen.id', 'magang.dosen_id'), $dir), 'perusahaan' => fn ($q, $dir) => $q->orderBy(\App\Models\Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'), $dir), 'tanggal_mulai' => 'tanggal_mulai', 'tanggal_selesai' => 'tanggal_selesai', 'status_magang' => 'status_magang', 'created_at' => 'created_at'], 'created_at'
        );

        if ($request->string('export')->toString() === 'excel') {
            return ExcelXmlExporter::download('monitoring-magang', ['Mahasiswa', 'NIM', 'Dosen', 'Perusahaan', 'Mulai', 'Selesai', 'Status', 'Logbook'], $query->lazy(500)->map(
                fn ($item) => [$item->mahasiswa?->nama ?? '-', $item->mahasiswa?->nim ?? '-', $item->dosen?->nama ?? '-', $item->instansi?->nama_instansi ?? '-', $item->tanggal_mulai?->format('d M Y') ?? '-', $item->tanggal_selesai?->format('d M Y') ?? '-', $item->status_magang, $item->log_kegiatan_count]
            ));
        }

        $monitoring = $query->paginate($perPage)->withQueryString();
        $monitoring->getCollection()->transform(function (Magang $item) {
            $totalHari = $item->tanggal_mulai && $item->tanggal_selesai
                ? max(1, $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1) : 0;
            $item->logbook_progress = $totalHari > 0 ? min(100, round(($item->log_kegiatan_count / $totalHari) * 100)) : 0;
            return $item;
        });

        return view('admin.monitoring.index', compact('monitoring', 'search', 'status', 'sort', 'direction', 'perPage'));
    }

    private function query(?string $search, ?string $status)
    {
        return Magang::query()
            ->withCount([
                'logKegiatan',
                'logKegiatan as logbook_disetujui_count' => fn ($query) => $query->where('status_validasi', 'disetujui'),
                'logKegiatan as logbook_menunggu_count' => fn ($query) => $query->where('status_validasi', 'menunggu'),
                'logKegiatan as logbook_ditolak_count' => fn ($query) => $query->where('status_validasi', 'ditolak'),
            ])
            ->with(['mahasiswa', 'dosen', 'instansi'])
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
}