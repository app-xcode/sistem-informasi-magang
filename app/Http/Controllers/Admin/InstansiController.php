<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InstansiRequest;
use App\Models\Instansi;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstansiController extends Controller
{
    public function index(): View
    {
        $search = request('q');

        $instansi = Instansi::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama_instansi', 'like', "%{$search}%")
                        ->orWhere('alamat', 'like', "%{$search}%")
                        ->orWhere('no_telp', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('penanggung_jawab', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.perusahaan.index', compact('instansi', 'search'));
    }

    public function create(): View
    {
        return view('admin.perusahaan.create');
    }

    public function store(InstansiRequest $request): RedirectResponse
    {
        Instansi::create($request->validated());

        return redirect()
            ->route('admin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil ditambahkan.');
    }

    public function edit(Instansi $perusahaan): View
    {
        return view('admin.perusahaan.edit', compact('perusahaan'));
    }

    public function update(InstansiRequest $request, Instansi $perusahaan): RedirectResponse
    {
        $perusahaan->update($request->validated());

        return redirect()
            ->route('admin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil diperbarui.');
    }

    public function destroy(Instansi $perusahaan): RedirectResponse
    {
        if ($perusahaan->magang()->exists()) {
            return back()->with('error', 'Perusahaan tidak dapat dihapus karena sudah memiliki data magang.');
        }

        $perusahaan->delete();

        return redirect()
            ->route('admin.perusahaan.index')
            ->with('success', 'Data perusahaan berhasil dihapus.');
    }
}