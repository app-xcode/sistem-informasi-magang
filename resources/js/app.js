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
        [/^Validasi$/i, 'fa-check-double'],
        [/^Perbarui( Profil| Password| Data)?$/i, 'fa-floppy-disk'],
        [/^Ubah Password$/i, 'fa-key'],
        [/^Ganti Password$/i, 'fa-key'],
        [/^Kirim$/i, 'fa-paper-plane'],
        [/^Lihat Detail$/i, 'fa-eye'],
        [/^Tambah$/i, 'fa-plus'],
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

    document.querySelectorAll('[data-datatable]').forEach((wrapper) => {
        const table = wrapper.querySelector('table');
        if (!table) return;

        const search = wrapper.querySelector('[data-table-search]');
        const exportButton = wrapper.querySelector('[data-table-export]');
        const title = wrapper.querySelector('.datatable-title');
        const headers = [...table.querySelectorAll('thead th')];
        const rows = [...table.querySelectorAll('tbody tr')].filter((row) => row.querySelectorAll('td').length);

        if (title && headers.length) {
            title.textContent = title.textContent === 'Data'
                ? `${headers[0].textContent.trim()} · ${rows.length} data`
                : title.textContent;
        }

        const getCells = (row) => [...row.querySelectorAll('td')];

        search?.addEventListener('input', () => {
            const query = search.value.toLowerCase().trim();
            rows.forEach((row) => {
                row.hidden = query !== '' && !row.textContent.toLowerCase().includes(query);
            });
        });

        headers.forEach((header, index) => {
            header.classList.add('cursor-pointer', 'select-none');
            header.title = 'Klik untuk mengurutkan';
            header.addEventListener('click', () => {
                const direction = header.dataset.sortDirection === 'asc' ? 'desc' : 'asc';
                headers.forEach((item) => delete item.dataset.sortDirection);
                header.dataset.sortDirection = direction;

                const tbody = table.querySelector('tbody');
                const sorted = [...rows].sort((a, b) => {
                    const av = getCells(a)[index]?.textContent.trim().toLowerCase() ?? '';
                    const bv = getCells(b)[index]?.textContent.trim().toLowerCase() ?? '';
                    const an = Number(av.replace(/[^0-9.-]+/g, ''));
                    const bn = Number(bv.replace(/[^0-9.-]+/g, ''));
                    const numeric = av !== '' && bv !== '' && Number.isFinite(an) && Number.isFinite(bn);
                    const result = numeric ? an - bn : av.localeCompare(bv, 'id');
                    return direction === 'asc' ? result : -result;
                });

                sorted.forEach((row) => tbody.appendChild(row));
            });
        });

        exportButton?.addEventListener('click', () => {
            const lines = [];
            const head = headers.map((cell) => `"${cell.textContent.trim().replace(/"/g, '""')}"`);
            lines.push(head.join(','));

            rows.filter((row) => !row.hidden).forEach((row) => {
                const cells = getCells(row).map((cell) => `"${cell.textContent.trim().replace(/"/g, '""')}"`);
                lines.push(cells.join(','));
            });

            const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'data-magang.csv';
            link.click();
            URL.revokeObjectURL(url);
        });
    });
});