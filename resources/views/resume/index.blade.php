<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resumé Wizard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
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

<nav class="bg-gray-900 border-b border-gray-800 p-4">
    <div class="max-w-5xl mx-auto flex justify-between items-center">
        <a href="/" class="text-xl font-bold bg-gradient-to-r from-brand-500 to-fuchsia-500 bg-clip-text text-transparent">Resumé PRO</a>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-400">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm border border-gray-700 px-3 py-1.5 rounded hover:bg-gray-800 transition">Logout</button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-12" x-data="resumeWizard()">

    <!-- Progress Indicator -->
    <div class="mb-10 max-w-3xl mx-auto">
        <div class="flex items-center justify-between relative">
            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-800 -z-10 rounded-full"></div>
            <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-brand-500 -z-10 rounded-full transition-all duration-500" :style="'width: ' + ((step - 1) / 4 * 100) + '%'"></div>
            
            <template x-for="i in 5">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                        :class="step >= i ? 'bg-brand-500 text-white shadow-[0_0_15px_rgba(139,92,246,0.5)]' : 'bg-gray-800 text-gray-500'">
                        <span x-text="i"></span>
                    </div>
                </div>
            </template>
        </div>
        <div class="flex justify-between text-[11px] text-gray-400 mt-3 px-1 font-medium select-none">
            <span>Personal</span>
            <span>Skills</span>
            <span>Education</span>
            <span>Projects</span>
            <span>Review</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 text-green-400 rounded-lg text-center font-medium max-w-3xl mx-auto">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('resume.save') }}" method="POST" id="resume-form" class="relative max-w-3xl mx-auto" x-show="step < 5" novalidate enctype="multipart/form-data">
        @csrf

        <!-- Step 1: Personal Info -->
        <div x-show="step === 1" x-transition.opacity.duration.300ms class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-xl">
            <div class="flex justify-between items-start mb-6">
                <h2 class="text-3xl font-bold text-white">Let's start with the basics</h2>
                <div class="relative group">
                    <div class="w-24 h-24 rounded-xl border-2 border-dashed border-gray-700 overflow-hidden flex items-center justify-center bg-gray-950 hover:border-brand-500 transition cursor-pointer" @click="$refs.photoInput.click()">
                        @if(!empty($profile->photo))
                            <img src="{{ asset('storage/' . $profile->photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[10px] text-gray-500 font-medium">PHOTO</span>
                            </div>
                        @endif
                    </div>
                    <input type="file" name="photo" x-ref="photoInput" class="hidden" accept="image/*">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-400">Full Name</label>
                    <input type="text" name="name" value="{{ $profile->name ?? Auth::user()->name }}" required class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-400">Professional Title</label>
                    <input type="text" name="title" value="{{ $profile->title ?? '' }}" placeholder="e.g. Full Stack Developer" list="titles" required class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    <datalist id="titles">
                        @foreach($suggestions['titles'] as $titleOption)
                            <option value="{{ $titleOption }}"></option>
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-400">Email Address</label>
                    <input type="email" name="email" value="{{ $profile->email ?? Auth::user()->email }}" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-400">Phone Number</label>
                    <input type="text" name="phone" value="{{ $profile->phone ?? '' }}" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-400">LinkedIn URL</label>
                    <input type="text" name="linkedin_url" value="{{ $profile->linkedin_url ?? '' }}" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-400">GitHub URL</label>
                    <input type="text" name="github_url" value="{{ $profile->github_url ?? '' }}" class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                </div>
                
                <div class="md:col-span-2 relative">
                    <label class="block text-sm font-medium mb-1 text-gray-400 flex justify-between">
                        <span>Professional Summary</span>
                    </label>
                    <textarea name="bio" rows="4" placeholder="Craft a compelling summary about your expertise and goals..." class="w-full bg-gray-950 border border-gray-800 rounded-lg px-4 py-3 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">{{ $profile->bio ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Step 2: Skills with Auto-suggestions -->
        <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;" class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-xl relative">
            <h2 class="text-3xl font-bold mb-2 text-white">What's your Tech Stack?</h2>
            
            <div class="space-y-4">
                <template x-for="(skill, index) in skills" :key="index">
                    <div class="flex flex-col md:flex-row gap-4 items-start group bg-gray-950/50 p-3 rounded-xl border border-gray-800 focus-within:border-brand-500 transition">
                        <div class="w-full md:w-1/3">
                            <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1 uppercase tracking-wide">Category</label>
                            <div class="relative w-full">
                                <select x-show="!skill.isCustomCategory" x-model="skill.category" :name="!skill.isCustomCategory ? 'skills['+index+'][category]' : ''" @change="if($event.target.value === 'CUSTOM') { skill.isCustomCategory = true; skill.category = ''; }" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                                    <option value="Languages">Languages</option>
                                    <option value="Frameworks">Frameworks & Libraries</option>
                                    <option value="Tools">Tools & Core Tech</option>
                                    <option value="Integrations">Integrations & APIs</option>
                                    <template x-for="catOpt in Object.keys(categorySuggestions).filter(c => !['Languages', 'Frameworks', 'Tools', 'Integrations'].includes(c))" :key="catOpt">
                                        <option :value="catOpt" x-text="catOpt"></option>
                                    </template>
                                    <option value="CUSTOM">+ Add Custom Category...</option>
                                </select>
                                
                                <div x-show="skill.isCustomCategory" style="display: none;" class="flex gap-2 w-full relative">
                                    <input type="text" x-model="skill.category" :name="skill.isCustomCategory ? 'skills['+index+'][category]' : ''" placeholder="Type new category..." class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                                    <button type="button" @click="skill.isCustomCategory = false; skill.category = 'Languages';" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white" title="Cancel">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="w-full md:w-2/3 relative">
                            <div class="flex flex-col md:flex-row gap-4 w-full">
                                <div class="flex-grow">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1 uppercase tracking-wide">Skill Name</label>
                                    <input type="text" x-model="skill.name" :name="'skills['+index+'][name]'" :list="'suggestions-' + index" placeholder="e.g. PHP, Laravel" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                                    <datalist :id="'suggestions-' + index">
                                        <template x-for="opt in (categorySuggestions[skill.category] || [])" :key="opt">
                                            <option :value="opt"></option>
                                        </template>
                                    </datalist>
                                </div>
                                <div class="flex-shrink-0">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1 ml-1 uppercase tracking-wide">Expertise</label>
                                    <div class="flex items-center gap-1 bg-gray-900 border border-gray-700 rounded-lg px-3 py-1.5 h-[38px]">
                                        <input type="hidden" :name="'skills['+index+'][proficiency]'" x-model="skill.proficiency">
                                        <template x-for="star in 5">
                                            <button type="button" @click="skill.proficiency = star" class="focus:outline-none transition transform hover:scale-110">
                                                <svg class="w-5 h-5" :class="skill.proficiency >= star ? 'text-yellow-400 fill-current' : 'text-gray-600'" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" @click="removeSkill(index)" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 p-2 rounded-lg transition h-[38px] flex items-center justify-center" title="Remove">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <button type="button" @click="addSkill()" class="mt-6 flex items-center justify-center w-full py-3 rounded-xl border border-dashed border-gray-700 hover:border-brand-500 hover:bg-brand-500/5 text-sm text-gray-400 hover:text-brand-400 transition font-medium gap-2 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Another Skill
            </button>
        </div>

        <!-- Step 3: Education -->
        <div x-show="step === 3" x-transition.opacity.duration.300ms style="display: none;" class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-xl relative">
            <h2 class="text-3xl font-bold mb-2 text-white">Education & Certifications</h2>
            <p class="text-sm text-gray-400 mb-8 border-b border-gray-800 pb-4">Where did you study, and what certifications do you hold?</p>
            
            <div class="space-y-6">
                <template x-for="(edu, index) in educations" :key="index">
                    <div class="p-5 border border-gray-700/50 rounded-xl bg-gray-950/30 relative">
                        <button type="button" @click="removeEducation(index)" class="absolute top-4 right-4 text-gray-500 hover:text-red-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Institution / School</label>
                                <input type="text" x-model="edu.school" :name="'educations['+index+'][school]'" :list="'schools-' + index" required placeholder="e.g. MIT, Platzi" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition">
                                <datalist :id="'schools-' + index">
                                    @foreach($suggestions['schools'] as $schoolOption)
                                        <option value="{{ $schoolOption }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Year / Dates</label>
                                <input type="text" x-model="edu.year" :name="'educations['+index+'][year]'" placeholder="e.g. 2018 - 2022" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Degree / Certification Name</label>
                                <input type="text" x-model="edu.degree" :name="'educations['+index+'][degree]'" :list="'degrees-' + index" required placeholder="e.g. B.S. in Computer Science" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition">
                                <datalist :id="'degrees-' + index">
                                    @foreach($suggestions['degrees'] as $degreeOption)
                                        <option value="{{ $degreeOption }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <button type="button" @click="addEducation()" class="mt-6 flex items-center justify-center w-full py-3 rounded-xl border border-dashed border-gray-700 hover:border-brand-500 hover:bg-brand-500/5 text-sm text-gray-400 hover:text-brand-400 transition font-medium gap-2 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Education
            </button>
        </div>

        <!-- Step 4: Projects -->
        <div x-show="step === 4" x-transition.opacity.duration.300ms style="display: none;" class="bg-gray-900 border border-gray-800 rounded-2xl p-8 shadow-xl">
            <h2 class="text-3xl font-bold mb-2 text-white">Experience & Projects</h2>
            <p class="text-sm text-gray-400 mb-8 border-b border-gray-800 pb-4">Highlight your most impressive work, integrations applied, and your role.</p>
            
            <div class="space-y-6">
                <template x-for="(project, index) in projects" :key="index">
                    <div class="p-5 border border-gray-700/50 rounded-xl bg-gray-950/30 relative">
                        <button type="button" @click="removeProject(index)" class="absolute top-4 right-4 text-gray-500 hover:text-red-400 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Project Name</label>
                                <input type="text" x-model="project.name" :name="'projects['+index+'][name]'" required class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Live URL</label>
                                <input type="text" x-model="project.url" :name="'projects['+index+'][url]'" placeholder="https://" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Tech Stack (comma separated)</label>
                                <input type="text" x-model="project.stack" :name="'projects['+index+'][stack]'" placeholder="e.g. Vue.js, Laravel, Tailwind, Node.js" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition">
                            </div>
                            <div class="md:col-span-2 relative">
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-1 flex justify-between">
                                    <span>Description</span>
                                </label>
                                <textarea x-model="project.description" :name="'projects['+index+'][description]'" rows="3" placeholder="What did you build and accomplish?" class="w-full bg-gray-900 border border-gray-700 rounded-lg px-4 py-2.5 focus:ring-1 focus:ring-brand-500 transition"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <button type="button" @click="addProject()" class="mt-6 flex items-center justify-center w-full py-3 rounded-xl border border-dashed border-gray-700 hover:border-brand-500 hover:bg-brand-500/5 text-sm text-gray-400 hover:text-brand-400 transition font-medium gap-2 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Another Project
            </button>
        </div>
    </form>

    <!-- Step 5: Finalize (Must exist outside the form to properly contain sub-forms if needed, but we decoupled it) -->
    <form action="{{ route('resume.save') }}" method="POST" id="resume-form-final" class="w-full" x-show="step === 5">
        @csrf
        <div x-transition.opacity.duration.300ms class="bg-gray-900 border border-gray-800 rounded-2xl p-6 shadow-xl w-full">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Live Preview & Export</h2>
                    <p class="text-sm text-gray-400">Save your changes to see the preview and customize your export.</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-3">
                    <!-- Invisible fields to save everything before previewing! Wait, if we use a separate form for saving here we miss Alpine fields -->
                    <button type="button" @click="submitMainForm()" class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg font-medium border border-gray-700 transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Save Data
                    </button>
                    

                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-6 w-full">
                <!-- Controls Sidebar -->
                <div class="w-full md:w-[30%] space-y-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Translation Language</label>
                        <select x-model="selectedLang" class="w-full bg-gray-950 text-gray-300 border border-gray-800 rounded-lg px-3 py-3 focus:ring-1 focus:ring-brand-500 focus:outline-none text-sm transition appearance-none">
                            <option value="original">🌐 Original</option>
                            <option value="en">🇬🇧 English</option>
                            <option value="es">🇪🇸 Español</option>
                            <option value="pt">🇧🇷 Português</option>
                            <option value="fr">🇫🇷 Français</option>
                            <option value="de">🇩🇪 Deutsch</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Resume Theme</label>
                        <select x-model="selectedTemplate" class="w-full bg-gray-950 text-gray-300 border border-gray-800 rounded-lg px-3 py-3 focus:ring-1 focus:ring-brand-500 focus:outline-none text-sm transition appearance-none">
                            <option value="classic">Executive (2-Col)</option>
                            <option value="minimal">Minimalist (1-Col)</option>
                            <option value="creative">Creative (Colored)</option>
                            <option value="harvard">Harvard (Traditional)</option>
                            <option value="elegant">Elegant (Serif Centered)</option>
                            <option value="modern">Modern Dark (Premium)</option>
                            <option value="compact">Professional Compact</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative">
                                <input type="checkbox" name="show_qr" value="1" x-model="showQr" class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-800 rounded-full peer peer-checked:bg-brand-600 transition"></div>
                                <div class="absolute left-1 top-1 w-3 h-3 bg-gray-400 rounded-full transition transform peer-checked:translate-x-5 peer-checked:bg-white"></div>
                            </div>
                            <span class="text-xs font-semibold text-gray-400 uppercase group-hover:text-gray-300 transition">Include Portfolio QR</span>
                        </label>
                        <input type="hidden" name="show_qr" :value="showQr ? 1 : 0">
                    </div>

                    <div class="space-y-4 pt-2 border-t border-gray-800">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Target Company</label>
                            <input type="text" x-model="targetCompany" placeholder="e.g. Google" class="w-full bg-gray-950 text-gray-300 border border-gray-800 rounded-lg px-3 py-2.5 focus:ring-1 focus:ring-brand-500 focus:outline-none text-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Recruiter Name (Optional)</label>
                            <input type="text" x-model="targetRecruiter" placeholder="e.g. John Doe" class="w-full bg-gray-950 text-gray-300 border border-gray-800 rounded-lg px-3 py-2.5 focus:ring-1 focus:ring-brand-500 focus:outline-none text-sm transition">
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <a :href="'{{ route('resume.download') }}?lang=' + selectedLang + '&template=' + selectedTemplate + '&show_qr=' + (showQr ? 1 : 0)" class="bg-brand-600 hover:bg-brand-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-[0_0_15px_rgba(139,92,246,0.3)] transition flex items-center justify-center gap-2" target="_blank">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Resume
                        </a>

                        <a :href="'{{ route('resume.cover') }}?lang=' + selectedLang + '&company=' + encodeURIComponent(targetCompany) + '&recruiter=' + encodeURIComponent(targetRecruiter)" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-lg font-bold text-sm shadow-[0_0_15px_rgba(79,70,229,0.3)] transition flex items-center justify-center gap-2" target="_blank">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Cover Letter
                        </a>
                    </div>

                    <a href="/p/{{ \Illuminate\Support\Str::slug(Auth::user()->name) . '-' . Auth::user()->id }}" target="_blank" class="flex flex-col bg-gradient-to-br from-indigo-900 to-brand-900 border border-brand-500/30 rounded-xl p-4 mt-6 hover:scale-105 transition transform cursor-pointer decoration-none">
                        <span class="text-white font-bold text-sm mb-1 flex items-center justify-between w-full">
                            Public Portfolio <svg class="w-4 h-4 ml-2 text-brand-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </span>
                        <span class="text-xs text-brand-200">Share your digital resume via a beautiful public link securely.</span>
                    </a>
                </div>

                <!-- Live Preview Iframe -->
                <div class="w-full md:w-[70%]">
                    <div class="rounded-xl overflow-hidden border border-gray-800 bg-gray-950 relative w-full h-[650px]">
                        @if(session('success'))
                            <iframe 
                                :src="'{{ route('resume.preview') }}?lang=' + selectedLang + '&template=' + selectedTemplate + '&show_qr=' + (showQr ? 1 : 0)" 
                                class="w-full h-full border-none bg-white absolute inset-0 rounded-xl" 
                                title="PDF Preview">
                            </iframe>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 absolute inset-0">
                                <svg class="w-12 h-12 mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p>Haz click en <strong class="text-white">Save Data</strong> para cargar la plantilla.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>


    <!-- Sticky Footer Navigation -->
    <div class="mt-8 flex items-center justify-between max-w-3xl mx-auto pb-12">
        <button type="button" @click="step--" x-show="step > 1" class="px-6 py-2.5 rounded-lg border border-gray-700 bg-gray-800 text-white hover:bg-gray-700 font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> Back
        </button>
        <div x-show="step === 1"></div>
        <button type="button" @click="step++" x-show="step < 5" class="px-6 py-2.5 rounded-lg bg-white text-gray-900 hover:bg-gray-200 font-bold transition flex items-center gap-2">
            Next Step <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('resumeWizard', () => ({
            step: {{ session('success') ? 5 : 1 }},
            selectedLang: 'original',
            selectedTemplate: 'classic',
            showQr: {{ ($profile->show_qr ?? true) ? 'true' : 'false' }},
            targetCompany: '',
            targetRecruiter: '',
            skills: {!! json_encode($skills->map(fn($s) => ['name' => $s->name, 'category' => $s->category, 'proficiency' => $s->proficiency ?? 3])->toArray()) !!} || [],
            educations: {!! json_encode($educations->map(fn($e) => ['school' => $e->school, 'degree' => $e->degree, 'year' => $e->year])->toArray()) !!} || [],
            projects: {!! json_encode($projects->map(fn($p) => ['name' => $p->name, 'url' => $p->url ?? '', 'stack' => is_array($p->stack) ? implode(', ', $p->stack) : '', 'description' => $p->description ?? ''])->toArray()) !!} || [],
            
            categorySuggestions: (() => {
                let base = {
                    'Languages': ['PHP', 'JavaScript', 'TypeScript', 'Python', 'Ruby', 'Java', 'C#', 'C++', 'Go', 'Rust'],
                    'Frameworks': ['Laravel', 'Vue.js', 'React', 'Angular', 'Express.js', 'Django', 'Spring Boot', 'Tailwind CSS', 'Bootstrap'],
                    'Tools': ['WebSockets (Pusher/Reverb)', 'Docker', 'Git', 'Redis', 'AWS EC2', 'Linux', 'MySQL', 'PostgreSQL', 'MongoDB'],
                    'Integrations': ['MercadoPago API', 'Stripe Checkout', 'OpenAI ChatGPT API', 'REST APIs', 'GraphQL', 'AWS S3', 'Twilio API']
                };
                let dbSuggestions = {!! json_encode($suggestions['skillNames']) !!};
                let dbCategories = {!! json_encode($suggestions['skillCategories']) !!};
                
                dbCategories.forEach(cat => {
                    if (!base[cat]) base[cat] = [];
                });
                
                Object.keys(dbSuggestions).forEach(cat => {
                    if (!base[cat]) base[cat] = [];
                    base[cat] = [...new Set([...base[cat], ...dbSuggestions[cat]])];
                });
                return base;
            })(),

            init() {
                if (this.skills.length === 0) this.addSkill();
                if (this.educations.length === 0) this.addEducation();
                if (this.projects.length === 0) this.addProject();
            },
            
            addSkill() { this.skills.push({ name: '', category: 'Languages', proficiency: 3 }); },
            removeSkill(index) { this.skills.splice(index, 1); },
            addEducation() { this.educations.push({ school: '', degree: '', year: '' }); },
            removeEducation(index) { this.educations.splice(index, 1); },
            addProject() { this.projects.push({ name: '', url: '', stack: '', description: '' }); },
            removeProject(index) { this.projects.splice(index, 1); },

            submitMainForm() {
                document.getElementById('resume-form').submit();
            }
        }))
    })
</script>
</body>
</html>
