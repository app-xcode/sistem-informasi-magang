<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Magang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-400 flex items-center justify-center p-4">

    <div class="w-full max-w-5xl overflow-hidden rounded-3xl bg-white shadow-2xl lg:grid lg:grid-cols-2">

        {{-- Left / Branding --}}
        <div class="relative hidden min-h-[620px] overflow-hidden bg-slate-900 lg:flex">
            <div class="absolute inset-0"></div>

            <div class="absolute -right-24 -top-24 h-72 w-72 rounded-full bg-blue-400/20 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-20 h-80 w-80 rounded-full bg-indigo-400/20 blur-3xl"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 text-white">
                <div>
                    <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20 backdrop-blur">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <path d="M19 2.01H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.998 5 19.815 5 19.01c0-.101.009-.191.024-.273.112-.575.583-.717.987-.727H20c.018 0 .031-.009.049-.01H21V4.01c0-1.103-.897-2-2-2zm0 14H5v-11c0-.806.55-.988 1-1h7v7l2-1 2 1v-7h2v12z"></path>
                        </svg>
                    </div>

                    <p class="mb-3 text-sm font-medium uppercase tracking-[0.25em] text-slate-200">
                        Sistem Informasi
                    </p>

                    <h1 class="max-w-md text-4xl font-bold leading-tight">
                        Sistem Informasi Magang
                    </h1>

                    <p class="mt-6 max-w-md text-base leading-7 text-slate-300">
                        Kelola kegiatan magang mahasiswa secara terintegrasi,
                        mulai dari pengajuan hingga monitoring dan pelaporan.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold leading-tight max-w-md text-xl">
                        AMAR YUSNAIN MBOJO
                    </h4>
                    <p class="leading-tight max-w-md text-xl">
                        B02220199
                    </p>
                    <div class="text-sm text-slate-400">
                        © {{ date('Y') }} Sistem Informasi Magang
                    </div>
                </div>
            </div>
        </div>

        {{-- Right / Login --}}
        <div class="flex items-center p-7 sm:p-10 lg:p-12">

            <div class="w-full max-w-md mx-auto">

                {{-- Mobile Branding --}}
                <div class="mb-8 lg:hidden">
                    <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-slate-600 text-white shadow-lg shadow-slate-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                            <path d="M19 2.01H6c-1.206 0-3 .799-3 3v14c0 2.201 1.794 3 3 3h15v-2H6.012C5.55 19.998 5 19.815 5 19.01c0-.101.009-.191.024-.273.112-.575.583-.717.987-.727H20c.018 0 .031-.009.049-.01H21V4.01c0-1.103-.897-2-2-2zm0 14H5v-11c0-.806.55-.988 1-1h7v7l2-1 2 1v-7h2v12z"></path>
                        </svg>
                    </div>

                    <p class="text-sm font-medium text-slate-600">
                        Sistem Informasi Magang
                    </p>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                        Selamat Datang
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Masuk ke akun Anda untuk melanjutkan.
                    </p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.3 3.9 2.9 17a2 2 0 0 0 1.7 3h14.8a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" />
                    </svg>

                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                            Email
                        </label>

                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                    stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6.5h16A1.5 1.5 0 0 1 21.5 8v8A1.5 1.5 0 0 1 20 17.5H4A1.5 1.5 0 0 1 2.5 16V8A1.5 1.5 0 0 1 4 6.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m3.5 7.5 8.5 6 8.5-6" />
                                </svg>
                            </div>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:bg-white focus:ring-4 focus:ring-slate-500/10">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-slate-700">
                                Password
                            </label>
                        </div>

                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                    stroke-width="1.8" viewBox="0 0 24 24">
                                    <rect x="4" y="10" width="16" height="10" rx="2" />
                                    <path stroke-linecap="round" d="M8 10V7a4 4 0 0 1 8 0v3" />
                                </svg>
                            </div>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-4 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:bg-white focus:ring-4 focus:ring-slate-500/10">
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center">
                        <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-500">
                            <input
                                type="checkbox"
                                name="remember"
                                class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-500">
                            Ingat saya
                        </label>
                    </div>

                    {{-- Button --}}
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-slate-900 py-3.5 text-sm font-semibold text-white shadow-lg shadow-slate-600/20 transition hover:bg-slate-700 hover:shadow-slate-600/30 focus:outline-none focus:ring-4 focus:ring-slate-500/20 active:scale-[0.99]">
                        Masuk ke Sistem

                        <svg
                            class="h-4 w-4 transition-transform group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 transition hover:text-slate-900">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        Kembali ke Home
                    </a>
                </div>

            </div>
        </div>

    </div>

</body>

</html>