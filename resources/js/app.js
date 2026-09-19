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

    const normalizeLeadingPlus = (el) => {
        if (!/^\+\s*Tambah\b/i.test(el.textContent.trim())) return;

        el.childNodes.forEach((node) => {
            if (node.nodeType !== Node.TEXT_NODE) return;
            node.textContent = node.textContent.replace(/^\s*\+\s*/, ' ');
        });
    };

    document.querySelectorAll('main a, main button').forEach((el) => {
        normalizeLeadingPlus(el);

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

        if (title && headers.length && title.textContent.trim() === 'Data') {
            title.textContent = `${headers[0].textContent.trim()} · ${rows.length} data`;
        }

        const getCells = (row) => [...row.querySelectorAll('td')];

        const sortableHeaders = headers
            .map((header, index) => ({ header, index }))
            .filter(({ header }) => !header.hasAttribute('data-no-sort') && !/^(aksi|action)$/i.test(header.textContent.trim()));

        const getSortValue = (value) => {
            const text = value.trim().replace(/\s+/g, ' ');
            const dateMatch = text.match(/^(\d{1,2})\s+(Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agu|Sep|Okt|Nov|Des)\s+(\d{4})(?:\s+(\d{1,2}):(\d{2}))?/i);
            if (dateMatch) {
                const months = {jan:0,feb:1,mar:2,apr:3,mei:4,jun:5,jul:6,agu:7,sep:8,okt:9,nov:10,des:11};
                return new Date(
                    Number(dateMatch[3]),
                    months[dateMatch[2].toLowerCase()],
                    Number(dateMatch[1]),
                    Number(dateMatch[4] || 0),
                    Number(dateMatch[5] || 0)
                ).getTime();
            }

            const numericText = text.replace(/[^0-9,.-]+/g, '').replace(/\./g, '').replace(',', '.');
            if (numericText !== '' && /^-?\d+(\.\d+)?$/.test(numericText)) {
                return Number(numericText);
            }

            return text.toLocaleLowerCase('id-ID');
        };

        search?.addEventListener('input', () => {
            const query = search.value.toLocaleLowerCase('id-ID').trim();
            rows.forEach((row) => {
                row.hidden = query !== '' && !row.textContent.toLocaleLowerCase('id-ID').includes(query);
            });
        });

        sortableHeaders.forEach(({ header, index }) => {
            header.classList.add('cursor-pointer', 'select-none');
            header.setAttribute('aria-sort', 'none');
            header.title = 'Klik untuk mengurutkan';

            const indicator = document.createElement('span');
            indicator.className = 'datatable-sort-indicator ml-2 text-[10px] text-slate-300';
            indicator.textContent = '↕';
            header.appendChild(indicator);

            header.addEventListener('click', () => {
                const direction = header.dataset.sortDirection === 'asc' ? 'desc' : 'asc';

                sortableHeaders.forEach(({ header: item }) => {
                    delete item.dataset.sortDirection;
                    item.setAttribute('aria-sort', 'none');
                    const itemIndicator = item.querySelector('.datatable-sort-indicator');
                    if (itemIndicator) {
                        itemIndicator.textContent = '↕';
                        itemIndicator.className = 'datatable-sort-indicator ml-2 text-[10px] text-slate-300';
                    }
                });

                header.dataset.sortDirection = direction;
                header.setAttribute('aria-sort', direction);
                indicator.textContent = direction === 'asc' ? '↑' : '↓';
                indicator.className = 'datatable-sort-indicator ml-2 text-[10px] font-bold text-slate-900';

                const tbody = table.querySelector('tbody');
                const sorted = [...rows].sort((a, b) => {
                    const av = getSortValue(getCells(a)[index]?.textContent ?? '');
                    const bv = getSortValue(getCells(b)[index]?.textContent ?? '');

                    if (typeof av === 'number' && typeof bv === 'number') {
                        return direction === 'asc' ? av - bv : bv - av;
                    }

                    const result = String(av).localeCompare(String(bv), 'id-ID', { numeric: true, sensitivity: 'base' });
                    return direction === 'asc' ? result : -result;
                });

                sorted.forEach((row) => tbody.appendChild(row));
            });
        });

        exportButton?.addEventListener('click', () => {
            const exportColumns = headers
                .map((header, index) => ({ header, index }))
                .filter(({ header }) => !header.hasAttribute('data-no-export') && !/^(aksi|action)$/i.test(header.textContent.trim()));

            const lines = [];
            const escapeCsv = (value) => `"${value.replace(/"/g, '""')}"`;

            lines.push(exportColumns.map(({ header }) => escapeCsv(header.textContent.replace(/↕|↑|↓/g, '').trim())).join(';'));

            rows.filter((row) => !row.hidden).forEach((row) => {
                const cells = getCells(row);
                lines.push(exportColumns.map(({ index }) => escapeCsv(cells[index]?.textContent.trim() ?? '')).join(';'));
            });

            const csv = '\ufeff' + lines.join('\r\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const filename = (title?.textContent.trim() || 'data').replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '').toLowerCase() || 'data';
            link.href = url;
            link.download = `${filename}.csv`;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        });
    });
});