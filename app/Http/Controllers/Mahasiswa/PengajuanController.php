<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\PengajuanRequest;
use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Magang;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $search = trim((string) $request->query('q'));
        $status = $request->string('status')->toString();

        $query = $mahasiswa->magang()
            ->with(['dosen', 'instansi'])
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('judul_magang', 'like', "%{$search}%")
                    ->orWhereHas('instansi', fn ($i) => $i->where('nama_instansi', 'like', "%{$search}%"))
                    ->orWhereHas('dosen', fn ($d) => $d->where('nama', 'like', "%{$search}%"));
            }))
            ->when($status, fn ($q) => $q->where('status_pengajuan', $status));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'tanggal_pengajuan' => 'tanggal_pengajuan',
            'judul_magang' => 'judul_magang',
            'instansi' => fn ($q, $dir) => $q->orderBy(
                Instansi::select('nama_instansi')->whereColumn('instansi.id', 'magang.instansi_id'),
                $dir
            ),
            'dosen' => fn ($q, $dir) => $q->orderBy(
                Dosen::select('nama')->whereColumn('dosen.id', 'magang.dosen_id'),
                $dir
            ),
            'status_pengajuan' => 'status_pengajuan',
        ], 'tanggal_pengajuan');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('pengajuan-magang-mahasiswa', [
                'Tanggal Pengajuan', 'Judul Magang', 'Instansi', 'Pembimbing', 'Status', 'Alasan Penolakan',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->tanggal_pengajuan?->format('d M Y') ?? '-',
                $item->judul_magang ?? '-',
                $item->instansi?->nama_instansi ?? '-',
                $item->dosen?->nama ?? 'Belum ditentukan',
                str_replace('_', ' ', ucfirst($item->status_pengajuan)),
                $item->alasan_penolakan ?? '-',
            ]));
        }

        $pengajuan = $query->paginate($perPage)->withQueryString();

        return view('mahasiswa.pengajuan.index', compact(
            'mahasiswa', 'pengajuan', 'search', 'status', 'sort', 'direction', 'perPage'
        ));
    }

    public function create(): View|RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        if ($this->hasActiveApplication($mahasiswa)) {
            return redirect()->route('mahasiswa.status-magang')
                ->with('info', 'Anda masih memiliki pengajuan atau magang aktif.');
        }

        $instansi = Instansi::query()->orderBy('nama_instansi')->get();
        $dosen = Dosen::query()->orderBy('nama')->get();
        $selectedInstansi = request()->integer('instansi_id') ?: null;

        return view('mahasiswa.pengajuan.create', compact('instansi', 'dosen', 'selectedInstansi'));
    }

    public function store(PengajuanRequest $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        if ($this->hasActiveApplication($mahasiswa)) {
            return redirect()->route('mahasiswa.pengajuan')
                ->with('error', 'Anda masih memiliki pengajuan atau magang aktif.');
        }

        Magang::create([
            ...$request->validated(),
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal_pengajuan' => now()->toDateString(),
            'status_pengajuan' => 'diajukan',
            'status_magang' => 'belum_mulai',
            'alasan_penolakan' => null,
        ]);

        return redirect()->route('mahasiswa.pengajuan')
            ->with('success', 'Pengajuan magang berhasil dikirim dan menunggu persetujuan admin.');
    }

    private function hasActiveApplication($mahasiswa): bool
    {
        return $mahasiswa->magang()
            ->where(function ($query) {
                $query->where('status_pengajuan', 'diajukan')
                    ->orWhere(function ($q) {
                        $q->where('status_pengajuan', 'disetujui')
                            ->whereIn('status_magang', ['belum_mulai', 'berlangsung']);
                    });
            })->exists();
    }
}
