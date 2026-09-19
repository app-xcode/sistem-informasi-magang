<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\LogbookRequest;
use App\Models\LogKegiatan;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LogbookController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;

        $magang = $mahasiswa?->magang()
            ->with(['dosen', 'instansi'])
            ->where('status_pengajuan', 'disetujui')
            ->whereIn('status_magang', ['belum_mulai', 'berlangsung'])
            ->latest('id')
            ->first();

        if (! $magang) {
            return view('mahasiswa.logbook.index', [
                'mahasiswa' => $mahasiswa,
                'magang' => null,
                'logbook' => collect(),
                'search' => '',
                'status' => '',
                'sort' => 'tanggal',
                'direction' => 'desc',
                'perPage' => 10,
            ]);
        }

        $search = trim((string) $request->query('q'));
        $status = $request->string('status')->toString();

        $query = $magang->logKegiatan()
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('judul_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            }))
            ->when($status, fn ($q) => $q->where('status_validasi', $status));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'tanggal' => 'tanggal',
            'judul_kegiatan' => 'judul_kegiatan',
            'status_validasi' => 'status_validasi',
        ], 'tanggal');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('logbook-magang', [
                'Tanggal', 'Judul Kegiatan', 'Deskripsi', 'Status Validasi', 'Catatan Dosen',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->tanggal?->format('d M Y') ?? '-',
                $item->judul_kegiatan,
                $item->deskripsi,
                str_replace('_', ' ', ucfirst($item->status_validasi)),
                $item->catatan_dosen ?? '-',
            ]));
        }

        $logbook = $query->paginate($perPage)->withQueryString();

        return view('mahasiswa.logbook.index', compact(
            'mahasiswa', 'magang', 'logbook', 'search', 'status', 'sort', 'direction', 'perPage'
        ));
    }

    public function store(LogbookRequest $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 403);
        $magang = $this->activeMagang($mahasiswa);
        abort_unless($magang, 403);
        $this->validateTanggal($request, $magang);
        $data = $request->validated();

        if ($request->hasFile('bukti_kegiatan')) {
            $data['bukti_kegiatan'] = $request->file('bukti_kegiatan')->store('logbook', 'public');
        }

        $data['magang_id'] = $magang->id;
        $data['status_validasi'] = 'menunggu';
        $data['catatan_dosen'] = null;

        LogKegiatan::create($data);

        return back()->with('success', 'Kegiatan logbook berhasil ditambahkan dan menunggu validasi dosen.');
    }

    public function bukti(LogKegiatan $logbook): BinaryFileResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless($logbook->bukti_kegiatan, 404);
        $disk = Storage::disk('public');
        abort_unless($disk->exists($logbook->bukti_kegiatan), 404);
        return response()->file($disk->path($logbook->bukti_kegiatan));
    }

    public function edit(LogKegiatan $logbook): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless($logbook->status_validasi === 'menunggu', 403);
        $magang = $this->activeMagang($mahasiswa);
        abort_unless($magang && $logbook->magang_id === $magang->id, 403);
        return view('mahasiswa.logbook.edit', compact('logbook', 'magang'));
    }

    public function update(LogbookRequest $request, LogKegiatan $logbook): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);
        abort_unless($logbook->status_validasi === 'menunggu', 403);
        $magang = $this->activeMagang($mahasiswa);
        abort_unless($magang && $logbook->magang_id === $magang->id, 403);
        $this->validateTanggal($request, $magang);
        $data = $request->validated();

        if ($request->hasFile('bukti_kegiatan')) {
            $newPath = $request->file('bukti_kegiatan')->store('logbook', 'public');
            if ($logbook->bukti_kegiatan) {
                Storage::disk('public')->delete($logbook->bukti_kegiatan);
            }
            $data['bukti_kegiatan'] = $newPath;
        } else {
            $data['bukti_kegiatan'] = $logbook->bukti_kegiatan;
        }

        $data['status_validasi'] = 'menunggu';
        $data['catatan_dosen'] = null;
        $logbook->update($data);

        return redirect()->route('mahasiswa.logbook')
            ->with('success', 'Logbook berhasil diperbarui dan tetap menunggu validasi dosen.');
    }

    public function destroy(LogKegiatan $logbook): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa && $logbook->magang?->mahasiswa_id === $mahasiswa->id, 403);

        if ($logbook->status_validasi !== 'menunggu') {
            return back()->with('error', 'Logbook yang sudah divalidasi tidak dapat dihapus.');
        }

        if ($logbook->bukti_kegiatan) {
            Storage::disk('public')->delete($logbook->bukti_kegiatan);
        }

        $logbook->delete();
        return back()->with('success', 'Logbook berhasil dihapus.');
    }

    private function activeMagang($mahasiswa)
    {
        return $mahasiswa->magang()
            ->where('status_pengajuan', 'disetujui')
            ->whereIn('status_magang', ['belum_mulai', 'berlangsung'])
            ->latest('id')
            ->first();
    }

    private function validateTanggal(LogbookRequest $request, $magang): void
    {
        if ($magang->tanggal_mulai && $request->date('tanggal')->lt($magang->tanggal_mulai)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tanggal kegiatan tidak boleh sebelum tanggal mulai magang.',
            ]);
        }

        if ($magang->tanggal_selesai && $request->date('tanggal')->gt($magang->tanggal_selesai)) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tanggal kegiatan tidak boleh setelah tanggal selesai magang.',
            ]);
        }
    }
}
