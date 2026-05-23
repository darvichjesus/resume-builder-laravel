<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SaaS Software Suite</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed' } }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-950 text-gray-100 antialiased selection:bg-brand-500 selection:text-white">
    <div class="relative min-h-screen flex flex-col justify-between">
        <!-- Background Glow -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-20%] left-[-10%] w-[50%] h-[50%] rounded-full bg-brand-600/20 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-fuchsia-600/20 blur-[120px]"></div>
        </div>

        <!-- Navigation -->
        <nav class="relative z-10 w-full p-6 flex justify-between items-center max-w-7xl mx-auto">
            <div class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-brand-400 to-fuchsia-400 bg-clip-text text-transparent flex items-center gap-2">
                <svg class="w-8 h-8 text-brand-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7L12 12L22 7L12 2Z M2 17L12 22L22 17 M2 12L12 17L22 12"/></svg>
                DevSuite PRO
            </div>
            <div class="space-x-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-semibold hover:text-white transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm bg-white text-gray-950 px-5 py-2.5 rounded-full font-bold hover:bg-gray-200 shadow-lg shadow-white/10 transition">Sign Up Free</a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="relative z-10 flex-grow flex flex-col items-center justify-center text-center px-4 sm:px-6 lg:px-8 mt-12 mb-20">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-sm font-medium mb-8">
                <span class="flex h-2 w-2 rounded-full bg-brand-400"></span>
                Welcome to the future of professional productivity
            </div>
            <h1 class="text-5xl sm:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                One platform.<br>
                <span class="bg-gradient-to-r from-brand-400 via-fuchsia-400 to-rose-400 bg-clip-text text-transparent">Endless professional growth.</span>
            </h1>
            <p class="mt-4 text-xl text-gray-400 max-w-2xl mx-auto mb-16 leading-relaxed">
                Unlock a suite of powerful tools designed to help you build, promote, and manage your professional career with ease.
            </p>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto text-left w-full">
                <!-- Product 1: Resume Builder -->
                <div class="group p-8 bg-gray-900/50 border border-gray-800 rounded-3xl backdrop-blur-sm shadow-xl hover:border-brand-500/50 transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-14 h-14 bg-brand-500/20 rounded-2xl flex items-center justify-center mb-6 text-brand-400 shadow-inner group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3">Resumé Maker PRO</h3>
                    <p class="text-gray-400 leading-relaxed mb-8">The most advanced resume builder with dynamic categories, auto-suggestions, and premium PDF templates.</p>
                    <a href="{{ route('resume.index') }}" class="inline-flex items-center gap-2 text-brand-400 font-bold hover:text-brand-300 transition group-hover:translate-x-2">
                        Launch App <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>

                <!-- Product 2: AI Cover Letter (Coming soon placeholder) -->
                <div class="p-8 bg-gray-900/30 border border-dashed border-gray-800 rounded-3xl backdrop-blur-sm relative overflow-hidden opacity-80">
                    <div class="absolute top-4 right-4 bg-gray-800 text-[10px] uppercase tracking-widest font-bold px-2 py-1 rounded text-gray-500">Coming Soon</div>
                    <div class="w-14 h-14 bg-gray-800 rounded-2xl flex items-center justify-center mb-6 text-gray-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-600">AI Cover Letter</h3>
                    <p class="text-gray-600 leading-relaxed">Propel your applications with AI-generated cover letters tailored to specific jobs and your profile.</p>
                </div>

                <!-- Product 3: Portfolios (Coming soon placeholder) -->
                <div class="p-8 bg-gray-900/30 border border-dashed border-gray-800 rounded-3xl backdrop-blur-sm relative overflow-hidden opacity-80">
                    <div class="absolute top-4 right-4 bg-gray-800 text-[10px] uppercase tracking-widest font-bold px-2 py-1 rounded text-gray-500">Coming Soon</div>
                    <div class="w-14 h-14 bg-gray-800 rounded-2xl flex items-center justify-center mb-6 text-gray-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9-9c1.657 0 3 4.03 3 9s-1.343 9-3 9m0-18c-1.657 0-3 4.03-3 9s1.343 9 3 9m-9-9a9 9 0 019-9"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 text-gray-600">Public Portfolios</h3>
                    <p class="text-gray-600 leading-relaxed">Turn your resume data into a beautiful, shareable personal website hosted on our lightning-fast domain.</p>
                </div>
            </div>
        </main>

        <footer class="relative z-10 py-8 text-center text-gray-600 border-t border-gray-900/50">
            &copy; {{ date('Y') }} DevSuite PRO SaaS. All rights reserved. 
        </footer>
    </div>
</body>
</html>
