<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MahasiswaBimbinganController extends Controller
{
    public function index(Request $request)
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
                \App\Models\Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'),
                $dir
            ),
            'tanggal_mulai' => 'tanggal_mulai',
            'tanggal_selesai' => 'tanggal_selesai',
            'status_magang' => 'status_magang',
        ], 'mahasiswa');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('mahasiswa-bimbingan', [
                'Mahasiswa', 'NIM', 'Perusahaan', 'Mulai', 'Selesai', 'Status', 'Logbook Disetujui', 'Total Logbook',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->mahasiswa?->nama ?? '-',
                $item->mahasiswa?->nim ?? '-',
                $item->instansi?->nama_instansi ?? '-',
                $item->tanggal_mulai?->format('d M Y') ?? '-',
                $item->tanggal_selesai?->format('d M Y') ?? '-',
                str_replace('_', ' ', ucfirst($item->status_magang)),
                $item->logbook_disetujui_count,
                $item->log_kegiatan_count,
            ]));
        }

        $magang = $query->paginate($perPage)->withQueryString();

        return view('dosen.mahasiswa-bimbingan.index', compact(
            'magang', 'search', 'status', 'sort', 'direction', 'perPage'
        ));
    }

    public function show(Magang $magang): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $magang->dosen_id === $dosen->id && $magang->status_pengajuan === 'disetujui', 403);
        $magang->load([
            'mahasiswa.user',
            'instansi',
            'logKegiatan' => fn ($q) => $q->latest('tanggal'),
            'laporan' => fn ($q) => $q->latest('tanggal_upload'),
            'penilaian',
        ]);

        return view('dosen.mahasiswa-bimbingan.show', compact('magang'));
    }
}
