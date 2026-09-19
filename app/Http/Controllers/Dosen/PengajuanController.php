<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Models\Instansi;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function index(Request $request): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen, 403);

        $status = $request->string('status')->toString();
        $search = trim((string) $request->query('q'));

        $query = Magang::query()
            ->with(['mahasiswa', 'instansi'])
            ->where('dosen_id', $dosen->id)
            ->when($status, fn ($q) => $q->where('status_pengajuan', $status))
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->whereHas('mahasiswa', fn ($m) => $m
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%"))
                    ->orWhereHas('instansi', fn ($i) => $i->where('nama_instansi', 'like', "%{$search}%"))
                    ->orWhere('judul_magang', 'like', "%{$search}%");
            }));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'mahasiswa' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'),
                $dir
            ),
            'perusahaan' => fn ($q, $dir) => $q->orderBy(
                Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'),
                $dir
            ),
            'judul_magang' => 'judul_magang',
            'tanggal_pengajuan' => 'tanggal_pengajuan',
            'status_pengajuan' => 'status_pengajuan',
        ], 'tanggal_pengajuan');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('pengajuan-magang-dosen', [
                'Mahasiswa', 'NIM', 'Perusahaan', 'Judul Magang', 'Tanggal Pengajuan', 'Status',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->mahasiswa?->nama ?? '-',
                $item->mahasiswa?->nim ?? '-',
                $item->instansi?->nama_instansi ?? '-',
                $item->judul_magang ?? '-',
                $item->tanggal_pengajuan?->format('d M Y') ?? '-',
                str_replace('_', ' ', ucfirst($item->status_pengajuan)),
            ]));
        }

        $pengajuan = $query->paginate($perPage)->withQueryString();

        return view('dosen.pengajuan.index', compact(
            'pengajuan', 'status', 'search', 'sort', 'direction', 'perPage'
        ));
    }
}
