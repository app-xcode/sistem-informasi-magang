<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MahasiswaRequest;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MahasiswaController extends Controller
{
    public function index(): View
    {
        $search = request('q');

        $mahasiswa = Mahasiswa::query()
            ->with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nim', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('program_studi', 'like', "%{$search}%")
                        ->orWhereHas('user', fn ($user) => $user->where('email', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'search'));
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

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
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

            if ($request->filled('password')) {
                $user->password = $request->string('password')->toString();
            }

            $user->role = 'mahasiswa';
            $user->save();
        });

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa): RedirectResponse
    {
        if ($mahasiswa->magang()->exists()) {
            return back()->with('error', 'Mahasiswa tidak dapat dihapus karena sudah memiliki data magang.');
        }

        DB::transaction(function () use ($mahasiswa) {
            $user = $mahasiswa->user;
            $mahasiswa->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
