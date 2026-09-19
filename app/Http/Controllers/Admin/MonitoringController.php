<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        $monitoring = Magang::query()
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
                        ->orWhereHas('mahasiswa', fn ($q) => $q
                            ->where('nama', 'like', "%{$search}%")
                            ->orWhere('nim', 'like', "%{$search}%"))
                        ->orWhereHas('dosen', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('instansi', fn ($q) => $q->where('nama_instansi', 'like', "%{$search}%"));
                });
            })
            ->when($status, fn ($query, $status) => $query->where('status_magang', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $monitoring->getCollection()->transform(function (Magang $item) {
            $totalHari = $item->tanggal_mulai && $item->tanggal_selesai
                ? max(1, $item->tanggal_mulai->diffInDays($item->tanggal_selesai) + 1)
                : 0;

            $item->logbook_progress = $totalHari > 0
                ? min(100, round(($item->log_kegiatan_count / $totalHari) * 100))
                : 0;

            return $item;
        });

        return view('admin.monitoring.index', compact('monitoring', 'search', 'status'));
    }
}
