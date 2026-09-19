<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\LaporanRequest;
use App\Models\Laporan;
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
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $magang = $mahasiswa->magang()
            ->with(['instansi', 'dosen'])
            ->where('status_pengajuan', 'disetujui')
            ->whereIn('status_magang', ['belum_mulai', 'berlangsung', 'selesai'])
            ->latest('id')
            ->first();

        $search = trim((string) $request->query('q'));
        $status = $request->string('status')->toString();

        $query = $magang
            ? $magang->laporan()
                ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                    $query->where('nama_file', 'like', "%{$search}%")
                        ->orWhere('catatan_dosen', 'like', "%{$search}%");
                }))
                ->when($status, fn ($q) => $q->where('status', $status))
            : null;

        $canUpload = $magang && ! $magang->laporan()->where('status', 'belum_validasi')->exists();

        if ($query) {
            [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
                'nama_file' => 'nama_file',
                'tanggal_upload' => 'tanggal_upload',
                'status' => 'status',
            ], 'tanggal_upload');
        } else {
            $sort = 'tanggal_upload';
            $direction = 'desc';
            $perPage = in_array((int) $request->input('per_page', 10), [10, 25, 50, 100], true)
                ? (int) $request->input('per_page', 10)
                : 10;
        }

        if ($request->query('export') === 'excel') {
            $rows = $query
                ? $query->lazy(500)->map(fn ($item) => [
                    $item->nama_file,
                    $item->tanggal_upload?->format('d M Y H:i') ?? '-',
                    str_replace('_', ' ', ucfirst($item->status)),
                    $item->catatan_dosen ?? '-',
                ])
                : collect();

            return ExcelXmlExporter::download('laporan-magang', [
                'File', 'Tanggal Upload', 'Status', 'Catatan Dosen',
            ], $rows);
        }

        $laporan = $query
            ? $query->paginate($perPage)->withQueryString()
            : collect();

        return view('mahasiswa.laporan.index', compact(
            'mahasiswa', 'magang', 'laporan', 'canUpload', 'search', 'status', 'sort', 'direction', 'perPage'
        ));
    }

    public function store(LaporanRequest $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $magang = $mahasiswa->magang()
            ->where('status_pengajuan', 'disetujui')
            ->whereIn('status_magang', ['belum_mulai', 'berlangsung', 'selesai'])
            ->latest('id')->first();

        abort_unless($magang, 403);

        if ($magang->laporan()->where('status', 'belum_validasi')->exists()) {
            return back()->with('error', 'Masih ada laporan yang menunggu validasi dosen.');
        }

        $file = $request->file('file');
        $path = $file->store('laporan', 'public');

        $magang->laporan()->create([
            'nama_file' => $file->getClientOriginalName(),
            'file_path' => $path,
            'tanggal_upload' => now(),
            'status' => 'belum_validasi',
            'catatan_dosen' => null,
        ]);

        return back()->with('success', 'Laporan berhasil diunggah dan menunggu validasi dosen.');
    }

    public function download(Laporan $laporan)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $laporan->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless(Storage::disk('public')->exists($laporan->file_path), 404);
        return Storage::disk('public')->download($laporan->file_path, $laporan->nama_file);
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $laporan->magang?->mahasiswa_id === $mahasiswa->id, 403);

        if ($laporan->status !== 'belum_validasi') {
            return back()->with('error', 'Laporan yang sudah divalidasi tidak dapat dihapus.');
        }

        Storage::disk('public')->delete($laporan->file_path);
        $laporan->delete();

        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
