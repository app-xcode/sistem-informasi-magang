<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TempatMagangController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $query = Instansi::query()
            ->when($search, fn ($q) => $q->where(function ($query) use ($search) {
                $query->where('nama_instansi', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%")
                    ->orWhere('penanggung_jawab', 'like', "%{$search}%");
            }));

        [$query, $sort, $direction, $perPage] = DataTable::sort($query, $request, [
            'nama_instansi' => 'nama_instansi',
            'alamat' => 'alamat',
            'penanggung_jawab' => 'penanggung_jawab',
            'no_telp' => 'no_telp',
        ], 'nama_instansi');

        if ($request->query('export') === 'excel') {
            return ExcelXmlExporter::download('tempat-magang', [
                'Nama Instansi', 'Alamat', 'Telepon', 'Penanggung Jawab',
            ], $query->lazy(500)->map(fn ($item) => [
                $item->nama_instansi,
                $item->alamat ?? '-',
                $item->no_telp ?? '-',
                $item->penanggung_jawab ?? '-',
            ]));
        }

        $instansi = $query->paginate($perPage)->withQueryString();

        return view('mahasiswa.tempat-magang.index', compact(
            'instansi', 'search', 'sort', 'direction', 'perPage'
        ));
    }
}
