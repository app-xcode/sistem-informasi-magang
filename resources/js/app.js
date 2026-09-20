document.addEventListener('DOMContentLoaded', () => {
    const tableActionMap = [
        [/^Edit( Nilai)?$/i, 'fa-pen'],
        [/^Hapus$/i, 'fa-trash'],
        [/^Detail$/i, 'fa-eye'],
        [/^Download$/i, 'fa-download'],
        [/^Unduh$/i, 'fa-download'],
        [/^Lihat( bukti)?$/i, 'fa-eye'],
        [/^Beri Nilai$/i, 'fa-star'],
        [/^Validasi$/i, 'fa-check-double'],
        [/^Simpan Validasi$/i, 'fa-check-double'],
    ];

    const iconMap = [
        [/^\\+?\\s*Tambah\\b/i, 'fa-plus'],
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
        [/^Unduh$/i, 'fa-download'],
        [/^Upload$/i, 'fa-upload'],
        [/^Beri Nilai$/i, 'fa-star'],
        [/^Setujui$/i, 'fa-check'],
        [/^Tolak$/i, 'fa-xmark'],
        [/^Simpan Validasi$/i, 'fa-check-double'],
        [/^Validasi$/i, 'fa-check-double'],
        [/^Perbarui( Profil| Password| Data)?$/i, 'fa-floppy-disk'],
        [/^Ubah Password$/i, 'fa-key'],
        [/^Ganti Password$/i, 'fa-key'],
        [/^Kirim$/i, 'fa-paper-plane'],
        [/^Lihat Detail$/i, 'fa-eye'],
        [/^Tambah$/i, 'fa-plus'],
    ];

    document.querySelectorAll('main a, main button').forEach((el) => {
        if (/^\\+\\s*Tambah\\b/i.test(el.textContent.trim())) {
            el.childNodes.forEach((node) => {
                if (node.nodeType === Node.TEXT_NODE) {
                    node.textContent = node.textContent.replace(/^\\s*\\+\\s*/, ' ');
                }
            });
        }

        const label = el.textContent.trim().replace(/\\s+/g, ' ');
        const td = el.closest('td');
        const isLastTableCell = td && td.parentElement && td.cellIndex === td.parentElement.cells.length - 1;
        const actionMatch = isLastTableCell
            ? tableActionMap.find(([pattern]) => pattern.test(label))
            : null;

        if (actionMatch) {
            if (el.dataset.noIcon !== undefined) return;

            const icon = document.createElement('i');
            icon.className = `fa-solid ${actionMatch[1]} mr-2 w-4 text-center`;
            icon.setAttribute('aria-hidden', 'true');

            const span = document.createElement('span');
            span.className = 'table-action-label';
            span.textContent = label;

            el.textContent = '';
            el.append(icon, span);
            el.classList.add('table-action', 'inline-flex', 'items-center', 'gap-1.5');
            td.classList.add('table-action-cell');
            return;
        }

        if (el.dataset.noIcon !== undefined || el.querySelector('i')) return;

        const match = iconMap.find(([pattern]) => pattern.test(label));

        if (!match) return;

        const icon = document.createElement('i');
        icon.className = `fa-solid ${match[1]} mr-2 w-4 text-center`;
        icon.setAttribute('aria-hidden', 'true');
        el.insertBefore(icon, el.firstChild);
        el.classList.add('gap-1.5');
    });
});