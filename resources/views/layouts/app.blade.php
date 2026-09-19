<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Magang')</title>
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
</body>
</html>
