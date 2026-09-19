<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Magang;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen, 403);

        $search = trim((string) $request->query('q'));
        $status = $request->string('status')->toString();

        $query = Magang::query()
            ->with(['mahasiswa', 'instansi'])
            ->withCount([
                'logKegiatan',
                'logKegiatan as logbook_disetujui_count' => fn ($q) => $q->where('status_validasi', 'disetujui'),
                'logKegiatan as logbook_menunggu_count' => fn ($q) => $q->where('status_validasi', 'menunggu'),
                'laporan as laporan_count',
            ])
            ->where('dosen_id', $dosen->id)
            ->where('status_pengajuan', 'disetujui')
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->whereHas('mahasiswa', fn ($m) => $m
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%"))
                    ->orWhereHas('instansi', fn ($i) => $i->where('nama_instansi', 'like', "%{$search}%"));
            }))
            ->when($status, fn ($q) => $q->where('status_magang', $status));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'mahasiswa' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'),
                $dir
            ),
            'perusahaan' => fn ($q, $dir) => $q->orderBy(
                Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'),
                $dir
            ),
            'tanggal_mulai' => 'tanggal_mulai',
            'tanggal_selesai' => 'tanggal_selesai',
            'status_magang' => 'status_magang',
        ], 'mahasiswa');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('monitoring-magang-dosen', [
                'Mahasiswa', 'NIM', 'Perusahaan', 'Mulai', 'Selesai', 'Status', 'Logbook Disetujui', 'Total Logbook', 'Laporan',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->mahasiswa?->nama ?? '-',
                $item->mahasiswa?->nim ?? '-',
                $item->instansi?->nama_instansi ?? '-',
                $item->tanggal_mulai?->format('d M Y') ?? '-',
                $item->tanggal_selesai?->format('d M Y') ?? '-',
                str_replace('_', ' ', ucfirst($item->status_magang)),
                $item->logbook_disetujui_count,
                $item->log_kegiatan_count,
                $item->laporan_count,
            ]));
        }

        $magang = $query->paginate($perPage)->withQueryString();

        return view('dosen.monitoring.index', compact(
            'magang', 'search', 'status', 'sort', 'direction', 'perPage'
        ));
    }
}
