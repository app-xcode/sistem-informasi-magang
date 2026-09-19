<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\PasswordRequest;
use App\Http\Requests\Dosen\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $dosen = auth()->user()->dosen;
        abort_unless($dosen, 403);
        return view('dosen.profil.index', compact('dosen'));
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $dosen = $user->dosen;
        abort_unless($dosen, 403);
        $data = $request->validated();
        $user->update(['name'=>$data['nama'],'email'=>$data['email']]);
        $dosen->update(['nama'=>$data['nama'],'nidn'=>$data['nidn'],'no_hp'=>$data['no_hp'] ?? null,'alamat'=>$data['alamat'] ?? null]);
        return back()->with('success','Profil berhasil diperbarui.');
    }

    public function updatePassword(PasswordRequest $request): RedirectResponse
    {
        $request->user()->update(['password'=>Hash::make($request->validated('password'))]);
        return back()->with('success','Password berhasil diperbarui.');
    }
}