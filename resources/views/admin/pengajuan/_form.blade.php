@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="mahasiswa_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Mahasiswa <span class="text-rose-500">*</span></label>
        <select id="mahasiswa_id" name="mahasiswa_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <option value="">Pilih mahasiswa</option>
            @foreach($mahasiswa as $item)
                <option value="{{ $item->id }}" @selected(old('mahasiswa_id', $pengajuan->mahasiswa_id ?? '') == $item->id)>{{ $item->nim }} — {{ $item->nama }}</option>
            @endforeach
        </select>
        @error('mahasiswa_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="dosen_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Dosen Pembimbing <span class="text-rose-500">*</span></label>
        <select id="dosen_id" name="dosen_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <option value="">Pilih dosen</option>
            @foreach($dosen as $item)
                <option value="{{ $item->id }}" @selected(old('dosen_id', $pengajuan->dosen_id ?? '') == $item->id)>{{ $item->nidn }} — {{ $item->nama }}</option>
            @endforeach
        </select>
        @error('dosen_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="instansi_id" class="mb-1.5 block text-sm font-semibold text-slate-700">Perusahaan / Instansi <span class="text-rose-500">*</span></label>
        <select id="instansi_id" name="instansi_id" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            <option value="">Pilih perusahaan</option>
            @foreach($instansi as $item)
                <option value="{{ $item->id }}" @selected(old('instansi_id', $pengajuan->instansi_id ?? '') == $item->id)>{{ $item->nama_instansi }}</option>
            @endforeach
        </select>
        @error('instansi_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="status_pengajuan" class="mb-1.5 block text-sm font-semibold text-slate-700">Status Pengajuan <span class="text-rose-500">*</span></label>
        <select id="status_pengajuan" name="status_pengajuan" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
            @foreach(['diajukan' => 'Diajukan', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status_pengajuan', $pengajuan->status_pengajuan ?? 'diajukan') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status_pengajuan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="judul_magang" class="mb-1.5 block text-sm font-semibold text-slate-700">Judul Magang <span class="text-rose-500">*</span></label>
        <input id="judul_magang" name="judul_magang" value="{{ old('judul_magang', $pengajuan->judul_magang ?? '') }}" required maxlength="255"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('judul_magang')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_pengajuan" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Pengajuan <span class="text-rose-500">*</span></label>
        <input id="tanggal_pengajuan" name="tanggal_pengajuan" type="date" value="{{ old('tanggal_pengajuan', isset($pengajuan) && $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('Y-m-d') : now()->format('Y-m-d')) }}" required
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('tanggal_pengajuan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_mulai" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Mulai</label>
        <input id="tanggal_mulai" name="tanggal_mulai" type="date" value="{{ old('tanggal_mulai', isset($pengajuan) && $pengajuan->tanggal_mulai ? $pengajuan->tanggal_mulai->format('Y-m-d') : '') }}"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('tanggal_mulai')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="tanggal_selesai" class="mb-1.5 block text-sm font-semibold text-slate-700">Tanggal Selesai</label>
        <input id="tanggal_selesai" name="tanggal_selesai" type="date" value="{{ old('tanggal_selesai', isset($pengajuan) && $pengajuan->tanggal_selesai ? $pengajuan->tanggal_selesai->format('Y-m-d') : '') }}"
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
        @error('tanggal_selesai')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="keterangan" class="mb-1.5 block text-sm font-semibold text-slate-700">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('keterangan', $pengajuan->keterangan ?? '') }}</textarea>
        @error('keterangan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="alasan_penolakan" class="mb-1.5 block text-sm font-semibold text-slate-700">Alasan Penolakan</label>
        <textarea id="alasan_penolakan" name="alasan_penolakan" rows="3" placeholder="Wajib diisi jika status ditolak..."
            class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('alasan_penolakan', $pengajuan->alasan_penolakan ?? '') }}</textarea>
        @error('alasan_penolakan')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">
    <a href="{{ route('admin.pengajuan.index') }}" class="rounded-lg border border-slate-300 px-4 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800">{{ $submitLabel }}</button>
</div>