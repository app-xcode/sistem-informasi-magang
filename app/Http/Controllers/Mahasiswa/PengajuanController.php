<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\PengajuanRequest;
use App\Models\Dosen;
use App\Models\Instansi;
use App\Models\Magang;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PengajuanController extends Controller
{
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        $pengajuan = $mahasiswa->magang()
            ->with(['dosen','instansi'])
            ->latest('tanggal_pengajuan')
            ->paginate(10)
            ->withQueryString();

        return view('mahasiswa.pengajuan.index', compact('mahasiswa','pengajuan'));
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

        return view('mahasiswa.pengajuan.create', compact('instansi','dosen','selectedInstansi'));
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
                $query->where('status_pengajuan','diajukan')
                    ->orWhere(function ($q) {
                        $q->where('status_pengajuan','disetujui')
                          ->whereIn('status_magang',['belum_mulai','berlangsung']);
                    });
            })->exists();
    }
}
