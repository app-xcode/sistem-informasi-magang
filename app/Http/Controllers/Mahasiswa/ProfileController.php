<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\PasswordRequest;
use App\Http\Requests\Mahasiswa\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);
        $mahasiswa->load('user');
        return view('mahasiswa.profil.index', compact('mahasiswa'));
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $mahasiswa = auth()->user()->mahasiswa;
        abort_unless($mahasiswa, 404);

        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->user->update($request->safe()->only(['name','email']));
            $mahasiswa->update($request->safe()->only(['nim','program_studi','no_hp','alamat']));
        });

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(PasswordRequest $request): RedirectResponse
    {
        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
