<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Sistem Informasi Magang')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased">
    <div class="min-h-screen lg:flex">
        <input id="sidebar-toggle" type="checkbox" class="peer sr-only">

        <label for="sidebar-toggle" class="fixed inset-0 z-30 hidden bg-slate-950/40 peer-checked:block lg:hidden" aria-label="Tutup sidebar"></label>

        <x-sidebar />

        <div class="min-w-0 flex-1">
            <x-topbar :title="$title ?? trim($__env->yieldContent('title', 'Dashboard'))" />

            <main class="px-4 py-6 sm:px-6 lg:px-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-6">
                    <x-alert />

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
<div id="file-preview-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/70 p-4" aria-hidden="true">
    <div class="flex h-[90vh] w-full max-w-6xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">
        <div class="flex shrink-0 items-center justify-between border-b border-slate-200 px-4 py-3">
            <div>
                <p class="text-sm font-semibold text-slate-900">Preview Bukti</p>
                <p id="file-preview-name" class="text-xs text-slate-500"></p>
            </div>
            <button type="button" data-close-file-preview class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50" aria-label="Tutup">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>
        <div id="file-preview-content" class="min-h-0 flex-1 bg-slate-100 p-3">
            <div class="flex h-full items-center justify-center text-sm text-slate-500">Memuat preview...</div>
        </div>
    </div>
</div>

</body>
</html>