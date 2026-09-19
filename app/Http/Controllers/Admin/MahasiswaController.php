<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\Response
    {
        $search = $request->string('q')->toString();
        [$query, $sort, $direction, $perPage] = DataTable::sort(
            $this->query($search),
            $request,
            ['nama' => 'nama', 'nim' => 'nim', 'program_studi' => 'program_studi', 'no_hp' => 'no_hp'],
            'created_at'
        );

        if ($request->string('export')->toString() === 'excel') {
            return ExcelXmlExporter::download('data-mahasiswa', ['Mahasiswa', 'Email', 'NIM', 'Program Studi', 'No. HP'], $query->lazy(500)->map(
                fn ($item) => [$item->nama, $item->user?->email ?? '-', $item->nim, $item->program_studi, $item->no_hp ?: '-']
            ));
        }

        $mahasiswa = $query->paginate($perPage)->withQueryString();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'search', 'sort', 'direction', 'perPage'));
    }

    private function query(?string $search)
    {
        return Mahasiswa::query()
            ->with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nim', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('program_studi', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('email', 'like', "%{$search}%"));
                });
            });
    }

    public function create(): View
    {
        return view('admin.mahasiswa.create');
    }

    public function store(MahasiswaRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = new User();
            $user->name = $request->string('nama')->toString();
            $user->email = $request->string('email')->toString();
            $user->password = $request->string('password')->toString();
            $user->role = 'mahasiswa';
            $user->save();

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->string('nim')->toString(),
                'nama' => $request->string('nama')->toString(),
                'program_studi' => $request->string('program_studi')->toString(),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
            ]);
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa): View
    {
        $mahasiswa->load('user');
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa): RedirectResponse
    {
        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update([
                'nim' => $request->string('nim')->toString(),
                'nama' => $request->string('nama')->toString(),
                'program_studi' => $request->string('program_studi')->toString(),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
            ]);

            $user = $mahasiswa->user;
            $user->name = $request->string('nama')->toString();
            $user->email = $request->string('email')->toString();
            if ($request->filled('password')) $user->password = $request->string('password')->toString();
            $user->role = 'mahasiswa';
            $user->save();
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        if ($mahasiswa->magang()->exists()) {
            return back()->with('error', 'Mahasiswa tidak dapat dihapus karena sudah memiliki data magang.');
        }

        DB::transaction(function () use ($mahasiswa) {
            $user = $mahasiswa->user;
            $mahasiswa->delete();
            if ($user) $user->delete();
        });

        return redirect()->route('admin.mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}