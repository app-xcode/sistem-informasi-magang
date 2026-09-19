<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MagangStatusRequest;
use App\Models\Magang;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DataMagangController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->toString();
        $status = $request->string('status')->toString();

        $magang = Magang::query()
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

        return view('admin.magang.index', compact('magang', 'search', 'status'));
    }

    public function show(Magang $magang): View
    {
        $magang->load(['mahasiswa.user', 'dosen.user', 'instansi']);

        abort_unless($magang->status_pengajuan === 'disetujui', 404);

        return view('admin.magang.show', compact('magang'));
    }

    public function updateStatus(MagangStatusRequest $request, Magang $magang): RedirectResponse
    {
        abort_unless($magang->status_pengajuan === 'disetujui', 404);

        $magang->update([
            'status_magang' => $request->validated('status_magang'),
        ]);

        return redirect()
            ->route('admin.magang.show', $magang)
            ->with('success', 'Status magang berhasil diperbarui.');
    }
}
