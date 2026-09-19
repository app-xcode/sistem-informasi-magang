document.addEventListener('DOMContentLoaded', () => {
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

        if (el.dataset.noIcon !== undefined || el.querySelector('i')) return;

        const label = el.textContent.trim().replace(/\\s+/g, ' ');
        const match = iconMap.find(([pattern]) => pattern.test(label));

        if (!match) return;

        const icon = document.createElement('i');
        icon.className = `fa-solid ${match[1]} mr-2 w-4 text-center`;
        icon.setAttribute('aria-hidden', 'true');
        el.insertBefore(icon, el.firstChild);
        el.classList.add('gap-1.5');
    });

    document.querySelectorAll('main table:not([data-no-datatable])').forEach((table) => {
        if (table.dataset.tableReady === 'true') return;
        table.dataset.tableReady = 'true';

        const wrapper = table.closest('[data-datatable]') || table.parentElement;
        const headers = [...table.querySelectorAll('thead th')];
        const rows = [...table.querySelectorAll('tbody tr')].filter((row) => row.querySelectorAll('td').length);

        if (!headers.length || !rows.length) return;

        if (!table.closest('[data-datatable]')) {
            const card = document.createElement('div');
            card.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm';
            table.parentNode.insertBefore(card, table);
            card.appendChild(table);

            const toolbar = document.createElement('div');
            toolbar.className = 'flex flex-col gap-3 border-b border-slate-200 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between';
            toolbar.innerHTML = `
                <div>
                    <p class="text-sm font-semibold text-slate-900">Data Tabel</p>
                    <p class="text-xs text-slate-500 datatable-count"></p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <label class="relative">
                        <span class="sr-only">Cari data</span>
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                        <input type="search" data-table-search placeholder="Cari data..." class="h-9 w-48 rounded-lg border border-slate-300 bg-white pl-8 pr-3 text-xs text-slate-700 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                    </label>
                    <button type="button" data-table-export data-no-icon class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-300 px-3 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        <i class="fa-solid fa-file-csv" aria-hidden="true"></i>
                        Export CSV
                    </button>
                </div>
            `;
            card.insertBefore(toolbar, table);
        }

        const container = table.closest('[data-datatable]') || table.parentElement;
        const search = container.querySelector('[data-table-search]');
        const exportButton = container.querySelector('[data-table-export]');
        const count = container.querySelector('.datatable-count');

        const getCells = (row) => [...row.querySelectorAll('td')];

        const updateCount = () => {
            const visible = rows.filter((row) => !row.hidden).length;
            if (count) count.textContent = `${visible} dari ${rows.length} data`;
        };

        search?.addEventListener('input', () => {
            const query = search.value.toLocaleLowerCase('id-ID').trim();
            rows.forEach((row) => {
                row.hidden = query !== '' && !row.textContent.toLocaleLowerCase('id-ID').includes(query);
            });
            updateCount();
        });

        const sortableHeaders = headers
            .map((header, index) => ({ header, index }))
            .filter(({ header }) => !header.hasAttribute('data-no-sort') && !/^(aksi|action)$/i.test(header.textContent.trim()));

        const getSortValue = (value) => {
            const text = value.trim().replace(/\\s+/g, ' ');
            const dateMatch = text.match(/^(\\d{1,2})\\s+(Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agu|Sep|Okt|Nov|Des)\\s+(\\d{4})(?:\\s+(\\d{1,2}):(\\d{2}))?/i);

            if (dateMatch) {
                const months = { jan:0, feb:1, mar:2, apr:3, mei:4, jun:5, jul:6, agu:7, sep:8, okt:9, nov:10, des:11 };
                return new Date(
                    Number(dateMatch[3]),
                    months[dateMatch[2].toLowerCase()],
                    Number(dateMatch[1]),
                    Number(dateMatch[4] || 0),
                    Number(dateMatch[5] || 0)
                ).getTime();
            }

            const numericText = text.replace(/[^0-9,.-]+/g, '').replace(/\\./g, '').replace(',', '.');
            if (numericText !== '' && /^-?\\d+(\\.\\d+)?$/.test(numericText)) {
                return Number(numericText);
            }

            return text.toLocaleLowerCase('id-ID');
        };

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
                indicator.className = 'datatable-sort-indicator ml-2 text-[10px] font-bold text-white';

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

            const escapeCsv = (value) => `"${value.replace(/"/g, '""')}"`;
            const lines = [
                exportColumns.map(({ header }) => escapeCsv(header.textContent.replace(/↕|↑|↓/g, '').trim())).join(';')
            ];

            rows.filter((row) => !row.hidden).forEach((row) => {
                const cells = getCells(row);
                lines.push(exportColumns.map(({ index }) => escapeCsv(cells[index]?.textContent.trim() ?? '')).join(';'));
            });

            const csv = '\\ufeff' + lines.join('\\r\\n');
            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'data-magang.csv';
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        });

        updateCount();
    });
});