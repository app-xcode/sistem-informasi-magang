import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const iconMap = [
        [/^\+?\s*Tambah\b/i, 'fa-plus'],
        [/^Cari$/i, 'fa-magnifying-glass'],
        [/^Filter$/i, 'fa-filter'],
        [/^Reset$/i, 'fa-rotate-left'],
        [/^Edit( Nilai)?$/i, 'fa-pen'],
        [/^Hapus$/i, 'fa-trash'],
        [/^Simpan( Validasi| Penilaian| Kegiatan)?$/i, 'fa-floppy-disk'],
        [/^Batal$/i, 'fa-xmark'],
        [/^Kembali$/i, 'fa-arrow-left'],
        [/^Detail$/i, 'fa-eye'],
        [/^Lihat( bukti)?$/i, 'fa-eye'],
        [/^Download$/i, 'fa-download'],
        [/^Upload$/i, 'fa-upload'],
        [/^Beri Nilai$/i, 'fa-star'],
        [/^Setujui$/i, 'fa-check'],
        [/^Tolak$/i, 'fa-xmark'],
        [/^Simpan Validasi$/i, 'fa-check-double'],
    ];

    document.querySelectorAll('main a, main button').forEach((el) => {
        if (el.dataset.noIcon !== undefined || el.querySelector('i')) return;

        const label = el.textContent.trim().replace(/\s+/g, ' ');
        const match = iconMap.find(([pattern]) => pattern.test(label));

        if (!match) return;

        const icon = document.createElement('i');
        icon.className = `fa-solid ${match[1]} mr-2 w-4 text-center`;
        icon.setAttribute('aria-hidden', 'true');
        el.insertBefore(icon, el.firstChild);
        el.classList.add('gap-1.5');
    });
});