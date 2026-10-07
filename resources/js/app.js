document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('select').forEach((select) => {
        if (select.parentElement?.classList.contains('select-wrapper')) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'select-wrapper';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
    });
});

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

const previewModal = document.getElementById('file-preview-modal');
const previewContent = document.getElementById('file-preview-content');
const previewName = document.getElementById('file-preview-name');

const closeFilePreview = () => {
    if (!previewModal) return;
    previewModal.classList.add('hidden');
    previewModal.classList.remove('flex');
    previewContent.innerHTML = '';
    document.body.classList.remove('overflow-hidden');
};

const openFilePreview = (url, type, name) => {
    if (!previewModal || !previewContent) return;

    previewName.textContent = name || '';
    previewContent.innerHTML = '';

    const showFileNotFound = () => {
        previewContent.innerHTML = '<div class="flex h-full items-center justify-center"><div class="text-center"><i class="fa-solid fa-file-circle-xmark text-3xl text-slate-400"></i><p class="mt-3 text-sm font-semibold text-slate-700">File tidak ditemukan</p><p class="mt-1 text-xs text-slate-500">Bukti tidak tersedia atau sudah tidak dapat diakses.</p></div></div>';
    };

    if (type === 'image') {
        const image = document.createElement('img');
        image.src = url;
        image.alt = name || 'Preview bukti';
        image.className = 'mx-auto h-full max-h-full max-w-full object-contain rounded-lg';
        image.addEventListener('error', showFileNotFound);
        previewContent.appendChild(image);
    } else {
        const iframe = document.createElement('iframe');
        iframe.src = url;
        iframe.title = name || 'Preview PDF';
        iframe.className = 'h-full w-full rounded-lg border border-slate-200 bg-white';
        iframe.addEventListener('error', showFileNotFound);
        iframe.addEventListener('load', () => {
            try {
                if (iframe.contentDocument?.body?.innerText?.trim() && !iframe.contentDocument.querySelector('embed, object, iframe')) {
                    const text = iframe.contentDocument.body.innerText.toLowerCase();
                    if (text.includes('404') || text.includes('not found') || text.includes('file tidak ditemukan')) showFileNotFound();
                }
            } catch (_) {
                // Cross-origin PDF viewers cannot be inspected; the iframe remains the preview.
            }
        });
        previewContent.appendChild(iframe);
    }

    previewModal.classList.remove('hidden');
    previewModal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
};

document.querySelectorAll('[data-file-preview]').forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
        event.preventDefault();
        openFilePreview(trigger.dataset.previewUrl, trigger.dataset.previewType, trigger.dataset.previewName);
    });
});

document.querySelectorAll('[data-close-file-preview]').forEach((button) => {
    button.addEventListener('click', closeFilePreview);
});

previewModal?.addEventListener('click', (event) => {
    if (event.target === previewModal) closeFilePreview();
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeFilePreview();
});
