@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="nim" class="mb-1.5 block text-sm font-semibold text-slate-700">NIM <span class="text-rose-500">*</span></label>
        <input id="nim" name="nim" value="{{ old('nim', $mahasiswa->nim ?? '') }}" required maxlength="30"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('nim')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="nama" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
        <input id="nama" name="nama" value="{{ old('nama', $mahasiswa->nama ?? '') }}" required maxlength="100"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('nama')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="program_studi" class="mb-1.5 block text-sm font-semibold text-slate-700">Program Studi <span class="text-rose-500">*</span></label>
        <input id="program_studi" name="program_studi" value="{{ old('program_studi', $mahasiswa->program_studi ?? '') }}" required maxlength="100"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('program_studi')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email <span class="text-rose-500">*</span></label>
        <input id="email" name="email" type="email" value="{{ old('email', $mahasiswa->user?->email ?? '') }}" required maxlength="255"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="no_hp" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor HP</label>
        <input id="no_hp" name="no_hp" value="{{ old('no_hp', $mahasiswa->no_hp ?? '') }}" maxlength="20"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('no_hp')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat</label>
        <textarea id="alamat" name="alamat" rows="4"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('alamat', $mahasiswa->alamat ?? '') }}</textarea>
        @error('alamat')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">
            Password {{ isset($mahasiswa) ? '(opsional)' : '' }} <span class="text-rose-500">{{ isset($mahasiswa) ? '' : '*' }}</span>
        </label>
        <input id="password" name="password" type="password" {{ isset($mahasiswa) ? '' : 'required' }} minlength="8"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" minlength="8"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
    </div>
</div>

<div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
    <a href="{{ route('admin.mahasiswa.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">{{ $submitLabel }}</button>
</div>
