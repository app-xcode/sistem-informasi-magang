@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="nama_instansi" class="mb-1.5 block text-sm font-semibold text-slate-700">Nama Perusahaan / Instansi <span class="text-rose-500">*</span></label>
        <input id="nama_instansi" name="nama_instansi" value="{{ old('nama_instansi', $perusahaan->nama_instansi ?? '') }}" required maxlength="150"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('nama_instansi')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat <span class="text-rose-500">*</span></label>
        <textarea id="alamat" name="alamat" rows="4" required
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('alamat', $perusahaan->alamat ?? '') }}</textarea>
        @error('alamat')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="no_telp" class="mb-1.5 block text-sm font-semibold text-slate-700">Nomor Telepon</label>
        <input id="no_telp" name="no_telp" value="{{ old('no_telp', $perusahaan->no_telp ?? '') }}" maxlength="20"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('no_telp')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="email" class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $perusahaan->email ?? '') }}" maxlength="150"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="penanggung_jawab" class="mb-1.5 block text-sm font-semibold text-slate-700">Penanggung Jawab</label>
        <input id="penanggung_jawab" name="penanggung_jawab" value="{{ old('penanggung_jawab', $perusahaan->penanggung_jawab ?? '') }}" maxlength="100"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('penanggung_jawab')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
    <a href="{{ route('admin.perusahaan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">{{ $submitLabel }}</button>
</div>