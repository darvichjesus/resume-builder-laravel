<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $profile->name }} | Portfolio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
<body class="bg-gray-950 text-gray-200 antialiased min-h-screen">

<!-- Top Interactive Gradient Banner -->
<div class="h-2 bg-gradient-to-r from-brand-500 via-fuchsia-500 to-indigo-500 w-full"></div>

<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="flex flex-col md:flex-row gap-10">
        
        <!-- Left Sidebar / Info -->
        <div class="w-full md:w-1/3">
            <div class="bg-gray-900 border border-gray-800 rounded-3xl p-8 sticky top-10 shadow-2xl">
                <div class="w-24 h-24 bg-gradient-to-br from-gray-800 to-gray-950 rounded-full border border-gray-700 flex items-center justify-center text-3xl font-bold text-white mb-6">
                    {{ substr($profile->name, 0, 1) }}
                </div>
                <h1 class="text-3xl font-bold text-white leading-tight">{{ $profile->name }}</h1>
                <p class="text-brand-400 font-medium text-lg mt-1 mb-8">{{ $profile->title }}</p>
                
                <div class="space-y-4">
                    @if(!empty($profile->email))
                    <div class="flex items-center gap-3 text-gray-400 text-sm">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span>{{ $profile->email }}</span>
                    </div>
                    @endif
                    @if(!empty($profile->phone))
                    <div class="flex items-center gap-3 text-gray-400 text-sm">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $profile->phone }}</span>
                    </div>
                    @endif
                </div>

                <div class="flex flex-wrap gap-3 mt-8">
                    @if(!empty($profile->linkedin_url))
                    <a href="{{ $profile->linkedin_url }}" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-brand-600 hover:text-white text-gray-400 rounded-full flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.768-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                    </a>
                    @endif
                    @if(!empty($profile->github_url))
                    <a href="{{ $profile->github_url }}" target="_blank" class="w-10 h-10 bg-gray-800 hover:bg-gray-700 hover:text-white text-gray-400 rounded-full flex items-center justify-center transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="w-full md:w-2/3 space-y-12 pb-20">
            @if(!empty($profile->bio))
            <div>
                <h2 class="text-xs tracking-widest text-brand-500 uppercase font-bold mb-4">About Me</h2>
                <div class="text-gray-300 leading-relaxed text-lg">{{ $profile->bio }}</div>
            </div>
            @endif

            @if(count($projects) > 0)
            <div>
                <h2 class="text-xs tracking-widest text-brand-500 uppercase font-bold mb-6">Experience & Projects</h2>
                <div class="space-y-6">
                    @foreach($projects as $project)
                    <div class="p-6 bg-gray-900/50 border border-gray-800 rounded-2xl hover:border-gray-700 transition">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-white">{{ $project->name }}</h3>
                            @if(!empty($project->url))
                                <a href="{{ $project->url }}" target="_blank" class="text-brand-400 hover:text-brand-300 text-sm">View Work &rarr;</a>
                            @endif
                        </div>
                        @if(!empty($project->stack))
                            <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($project->stack as $tech)
                                <span class="px-3 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">{{ trim($tech) }}</span>
                            @endforeach
                            </div>
                        @endif
                        <p class="text-gray-400 text-sm leading-relaxed">{{ $project->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(count($educations) > 0)
            <div>
                <h2 class="text-xs tracking-widest text-brand-500 uppercase font-bold mb-6">Education & Certifications</h2>
                <div class="space-y-4">
                    @foreach($educations as $edu)
                    <div class="flex justify-between items-center p-5 bg-gray-900 border border-gray-800 rounded-xl">
                        <div>
                            <h3 class="font-bold text-white">{{ $edu->degree }}</h3>
                            <p class="text-gray-400 text-sm mt-1">{{ $edu->school }}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-500">{{ $edu->year }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            @if(count($skills) > 0)
            <div>
                <h2 class="text-xs tracking-widest text-brand-500 uppercase font-bold mb-6">Technical Skills</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($skills as $category => $categorySkills)
                    <div class="p-6 bg-gray-900/30 border border-gray-800 rounded-2xl">
                        <h3 class="text-white font-semibold mb-4">{{ $category }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categorySkills as $skill)
                                <span class="px-3 py-1 bg-brand-500/10 text-brand-300 border border-brand-500/20 text-xs rounded-lg">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<footer class="text-center py-8 text-gray-600 text-sm border-t border-gray-900/50">
    Resume built with <span class="text-brand-500">Resumé PRO</span>
</footer>

</body>
</html>
