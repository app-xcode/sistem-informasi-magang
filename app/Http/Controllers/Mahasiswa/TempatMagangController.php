<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TempatMagangController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $instansi = Instansi::query()
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('nama_instansi','like',"%{$search}%")
                    ->orWhere('alamat','like',"%{$search}%")
                    ->orWhere('penanggung_jawab','like',"%{$search}%");
            }))
            ->orderBy('nama_instansi')
            ->paginate(12)
            ->withQueryString();

        return view('mahasiswa.tempat-magang.index', compact('instansi','search'));
    }
}
