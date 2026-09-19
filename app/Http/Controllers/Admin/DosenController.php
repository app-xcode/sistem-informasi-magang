<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DosenRequest;
use App\Models\Dosen;
use App\Models\User;
use App\Support\DataTable;
use App\Support\ExcelXmlExporter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\Response
    {
        $search = $request->string('q')->toString();
        [$query, $sort, $direction, $perPage] = DataTable::sort(
            $this->query($search), $request,
            ['nama' => 'nama', 'nidn' => 'nidn', 'no_hp' => 'no_hp', 'alamat' => 'alamat'], 'created_at'
        );

        if ($request->string('export')->toString() === 'excel') {
            return ExcelXmlExporter::download('data-dosen', ['Dosen', 'Email', 'NIDN', 'No. HP', 'Alamat'], $query->lazy(500)->map(
                fn ($item) => [$item->nama, $item->user?->email ?? '-', $item->nidn, $item->no_hp ?: '-', $item->alamat ?: '-']
            ));
        }

        $dosen = $query->paginate($perPage)->withQueryString();
        return view('admin.dosen.index', compact('dosen', 'search', 'sort', 'direction', 'perPage'));
    }

    private function query(?string $search)
    {
        return Dosen::query()->with('user')->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nidn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($user) => $user->where('email', 'like', "%{$search}%"));
            });
        });
    }

    public function create(): View { return view('admin.dosen.create'); }

    public function store(DosenRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $user = new User();
            $user->name = $request->string('nama')->toString();
            $user->email = $request->string('email')->toString();
            $user->password = $request->string('password')->toString();
            $user->role = 'dosen';
            $user->save();

            Dosen::create([
                'user_id' => $user->id,
                'nidn' => $request->string('nidn')->toString(),
                'nama' => $request->string('nama')->toString(),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
            ]);
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): View { $dosen->load('user'); return view('admin.dosen.edit', compact('dosen')); }

    public function update(DosenRequest $request, Dosen $dosen): RedirectResponse
    {
        DB::transaction(function () use ($request, $dosen) {
            $dosen->update([
                'nidn' => $request->string('nidn')->toString(),
                'nama' => $request->string('nama')->toString(),
                'no_hp' => $request->input('no_hp'),
                'alamat' => $request->input('alamat'),
            ]);

            $user = $dosen->user;
            $user->name = $request->string('nama')->toString();
            $user->email = $request->string('email')->toString();
            if ($request->filled('password')) $user->password = $request->string('password')->toString();
            $user->role = 'dosen';
            $user->save();
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        if ($dosen->magang()->exists()) return back()->with('error', 'Dosen tidak dapat dihapus karena sudah memiliki data magang.');
        if ($dosen->penilaian()->exists()) return back()->with('error', 'Dosen tidak dapat dihapus karena sudah memiliki data penilaian.');

        DB::transaction(function () use ($dosen) {
            $user = $dosen->user;
            $dosen->delete();
            if ($user) $user->delete();
        });

        return redirect()->route('admin.dosen.index')->with('success', 'Data dosen berhasil dihapus.');
    }
}