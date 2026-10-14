<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title','SamruddhiSilks')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind CDN (dev only) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tbRed: "#DA2E0C",
                        tbDark: "#1F2132",
                        tbSoft: "#D7A9A2",
                        tbWhite: "#FEFDFD",
                        paytmBlue: "#00baf2",
                        paytmDarkBlue: "#002970",
                        paytmBg: "#f5f7fb",
                    },
                    fontFamily: {
                        sans: ["Inter", "system-ui", "sans-serif"],
                    },
                    boxShadow: {
                        paytm: "0 10px 40px rgba(0,0,0,0.08)",
                    },
                    borderRadius: {
                        xl2: "1.25rem",
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-paytmBg text-tbDark antialiased">

    {{-- PAGE CONTENT --}}
    <main class="min-h-screen">
        {{-- NAVBAR --}}
<header class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">

        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img src="{{ asset($site->logo ?? '') }}" alt="SamruddhiSilks" class="h-9 w-auto" />
        </a>

        <nav class="hidden md:flex items-center gap-8 text-sm text-slate-700">
            <a href="#how" class="hover:text-tbRed transition">How it works</a>
            <a href="#offers" class="hover:text-tbRed transition">Offers</a>
            <a href="#download" class="hover:text-tbRed transition">Get App</a>
        </nav>

        <div class="hidden md:flex items-center gap-3 text-sm">
            <a
                href="https://play.google.com/store/apps/details?id=app.samruddhisilks.user"
                target="_blank"
                class="px-4 py-2 rounded-full bg-tbRed text-white font-semibold hover:brightness-110 shadow-paytm"
            >
                Get App
            </a>
        </div>

        <button
            id="mobile-menu-btn"
            class="md:hidden inline-flex items-center justify-center rounded-full border border-slate-200 bg-white p-2 text-slate-700"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div id="mobile-nav" class="md:hidden hidden border-t border-slate-200 bg-white">
        <nav class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex flex-col gap-3 text-sm text-slate-700">
            <a href="#how" class="py-1 hover:text-tbRed">How it works</a>
            <a href="#offers" class="py-1 hover:text-tbRed">Offers</a>
            <a href="#download" class="py-1 hover:text-tbRed">Get App</a>

            <a
                href="https://play.google.com/store/apps/details?id=app.samruddhisilks.user"
                target="_blank"
                class="mt-2 px-4 py-2 rounded-full bg-tbRed text-white font-semibold text-center shadow-paytm"
            >
                Get on Play Store
            </a>
        </nav>
    </div>
</header>

        @yield('content')
    </main>

    {{-- Footer (simple common footer for policy pages) --}}
    <footer class="bg-paytmBg border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5
                    flex flex-col md:flex-row items-center justify-between gap-3
                    text-[11px] text-slate-500">

            <div class="space-y-1">
                <p>SamruddhiSilks sells curated and exclusive products directly to customers.</p>
                <p>
                    © <span id="layoutYear"></span> SamruddhiSilks. All rights reserved.
                </p>
            </div>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('terms') }}" class="hover:text-tbRed">Terms</a>
                <a href="{{ route('privacy') }}" class="hover:text-tbRed">Privacy</a>
                <a href="{{ route('refund') }}" class="hover:text-tbRed">Refund Policy</a>
                <a href="{{ route('account-delete-request') }}" class="hover:text-tbRed">Delete Account</a>
            </div>
        </div>
    </footer>

    <script>
        const y = document.getElementById('layoutYear');
        if (y) y.textContent = new Date().getFullYear();
    </script>

    @stack('scripts')

</body>
</html>