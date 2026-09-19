<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use App\Http\Requests\Dosen\PenilaianRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen, 403);

        $search = trim((string) $request->query('q'));

        $query = Magang::query()
            ->with(['mahasiswa', 'instansi', 'penilaian'])
            ->where('dosen_id', $dosen->id)
            ->where('status_pengajuan', 'disetujui')
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->whereHas('mahasiswa', fn ($m) => $m
                    ->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%"))
                    ->orWhereHas('instansi', fn ($i) => $i->where('nama_instansi', 'like', "%{$search}%"));
            }));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'mahasiswa' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Mahasiswa::select('nama')->whereColumn('mahasiswa.id', 'magang.mahasiswa_id'),
                $dir
            ),
            'perusahaan' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'),
                $dir
            ),
            'nilai_akhir' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Penilaian::select('nilai_akhir')->whereColumn('penilaian.magang_id', 'magang.id'),
                $dir
            ),
            'tanggal_penilaian' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Penilaian::select('tanggal_penilaian')->whereColumn('penilaian.magang_id', 'magang.id'),
                $dir
            ),
        ], 'mahasiswa');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('penilaian-magang-dosen', [
                'Mahasiswa', 'NIM', 'Perusahaan', 'Nilai Akhir', 'Tanggal Penilaian', 'Catatan',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->mahasiswa?->nama ?? '-',
                $item->mahasiswa?->nim ?? '-',
                $item->instansi?->nama_instansi ?? '-',
                $item->penilaian?->nilai_akhir ?? 'Belum dinilai',
                $item->penilaian?->tanggal_penilaian?->format('d M Y') ?? '-',
                $item->penilaian?->catatan ?? '-',
            ]));
        }

        $magang = $query->paginate($perPage)->withQueryString();

        return view('dosen.penilaian.index', compact(
            'magang', 'search', 'sort', 'direction', 'perPage'
        ));
    }

    public function edit(Magang $magang): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $magang->dosen_id === $dosen->id && $magang->status_pengajuan === 'disetujui', 403);
        $magang->load(['mahasiswa', 'instansi', 'penilaian']);
        return view('dosen.penilaian.edit', compact('magang'));
    }

    public function store(PenilaianRequest $request, Magang $magang): RedirectResponse
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $magang->dosen_id === $dosen->id && $magang->status_pengajuan === 'disetujui', 403);
        $data = $request->validated();
        $data['dosen_id'] = $dosen->id;
        $data['tanggal_penilaian'] = now()->toDateString();
        $data['nilai_akhir'] = round(collect(['kedisiplinan', 'tanggung_jawab', 'kerja_sama', 'kemampuan_teknis', 'sikap'])->avg(fn ($k) => (float) $data[$k]), 2);
        $magang->penilaian()->updateOrCreate([], $data);
        return redirect()->route('dosen.penilaian')->with('success', 'Penilaian berhasil disimpan.');
    }
}
