<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\LaporanValidationRequest;
use App\Models\Laporan;
use App\Models\Instansi;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen, 403);

        $search = trim((string) $request->query('q'));
        $status = $request->string('status')->toString();

        $query = Laporan::query()
            ->with(['magang.mahasiswa', 'magang.instansi'])
            ->whereHas('magang', fn ($q) => $q
                ->where('dosen_id', $dosen->id)
                ->where('status_pengajuan', 'disetujui'))
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('nama_file', 'like', "%{$search}%")
                    ->orWhereHas('magang.mahasiswa', fn ($m) => $m
                        ->where('nama', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%"))
                    ->orWhereHas('magang.instansi', fn ($i) => $i->where('nama_instansi', 'like', "%{$search}%"));
            }))
            ->when($status, fn ($q) => $q->where('status', $status));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'mahasiswa' => fn ($q, $dir) => $q->orderBy(
                \App\Models\Mahasiswa::select('nama')
                    ->join('magang', 'magang.mahasiswa_id', '=', 'mahasiswa.id')
                    ->whereColumn('magang.id', 'laporan.magang_id'),
                $dir
            ),
            'file' => 'nama_file',
            'tanggal_upload' => 'tanggal_upload',
            'status' => 'status',
            'perusahaan' => fn ($q, $dir) => $q->orderBy(
                Instansi::select('nama_instansi')
                    ->join('magang', 'magang.instansi_id', '=', 'instansi.id')
                    ->whereColumn('magang.id', 'laporan.magang_id'),
                $dir
            ),
        ], 'tanggal_upload');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('laporan-magang-dosen', [
                'Mahasiswa', 'NIM', 'Perusahaan', 'File', 'Tanggal Upload', 'Status', 'Catatan Dosen',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->magang?->mahasiswa?->nama ?? '-',
                $item->magang?->mahasiswa?->nim ?? '-',
                $item->magang?->instansi?->nama_instansi ?? '-',
                $item->nama_file,
                $item->tanggal_upload?->format('d M Y H:i') ?? '-',
                str_replace('_', ' ', ucfirst($item->status)),
                $item->catatan_dosen ?? '-',
            ]));
        }

        $laporan = $query->paginate($perPage)->withQueryString();

        return view('dosen.laporan.index', compact(
            'laporan', 'search', 'status', 'sort', 'direction', 'perPage'
        ));
    }

    public function download(Laporan $laporan)
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $laporan->magang?->dosen_id === $dosen->id && $laporan->magang?->status_pengajuan === 'disetujui', 403);
        abort_unless(Storage::disk('public')->exists($laporan->file_path), 404);
        return Storage::disk('public')->download($laporan->file_path, $laporan->nama_file);
    }

    public function validateReport(LaporanValidationRequest $request, Laporan $laporan): RedirectResponse
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen && $laporan->magang?->dosen_id === $dosen->id && $laporan->magang?->status_pengajuan === 'disetujui', 403);
        $data = $request->validated();
        $laporan->update(['status' => $data['status'], 'catatan_dosen' => $data['catatan_dosen'] ?? null]);
        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}
