<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume</title>
    <style>
        @page { margin: 0; size: a4 portrait; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #334155; margin: 0; padding: 0; background-color: #ffffff; }
        
        .sidebar { 
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 30%;
            background-color: #0f172a;
            padding: 40px 20px;
            color: #ffffff;
            z-index: 100;
        }
        
        .main { 
            margin-left: 33%;
            padding: 40px 40px;
            width: 60%;
        }

        h2.sidebar-title { font-size: 11pt; text-transform: uppercase; letter-spacing: 1px; margin-top: 30px; margin-bottom: 12px; border-bottom: 1px solid #334155; padding-bottom: 5px; color: #cbd5e1; font-weight: bold; }
        .sidebar-content { color: #f8fafc; font-size: 8.5pt; line-height: 1.5; margin-bottom: 15px; word-wrap: break-word; }
        .skill-category { font-weight: bold; color: #94a3b8; font-size: 9pt; margin-bottom: 3px; }
        .skill-list { color: #f8fafc; font-size: 8.5pt; display: block; line-height: 1.4; margin-bottom: 15px; }

        h1.name { font-size: 26pt; margin: 0; color: #0f172a; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; line-height: 1.1; }
        .title { font-size: 12pt; color: #2563eb; font-weight: bold; margin-top: 8px; margin-bottom: 35px; }
        h2.main-title { font-size: 13pt; text-transform: uppercase; letter-spacing: 1px; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 6px; margin-bottom: 20px; font-weight: bold; }
        
        .project { margin-bottom: 25px; page-break-inside: avoid; }
        .project-header { font-size: 12pt; font-weight: bold; color: #0f172a; display: inline-block; width: 60%; }
        .project-url { font-size: 9pt; color: #2563eb; text-align: right; display: inline-block; width: 38%; vertical-align: top; }
        .project-stack { font-size: 9pt; color: #64748b; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 3px; }
        .edu-item { margin-bottom: 20px; page-break-inside: avoid; }

        .star-container { display: inline-block; white-space: nowrap; }
        .star-dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; margin-left: 2px; border: 1px solid #fbbf24; }
        .star-dot.filled { background-color: #fbbf24; }
        .star-dot.empty { background-color: transparent; }

        .photo-wrapper { text-align: center; margin-bottom: 20px; }
        .photo-wrapper img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #334155; }
    </style>
</head>
<body>
    <div class="sidebar">
        @if(!empty($profile->photo))
            <div class="photo-wrapper">
                <img src="{{ public_path('storage/' . $profile->photo) }}" alt="photo">
            </div>
        @endif

        @if(($showQr ?? true) && isset($portfolioUrl))
            <div style="margin-bottom: 20px; text-align: center;">
                <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->margin(0)->generate($portfolioUrl)) !!}" style="width: 80px; height: 80px; background: white; padding: 5px; border-radius: 4px;" />
            </div>
        @endif

        <h2 class="sidebar-title" style="margin-top: 0;">Contact</h2>
        <div class="sidebar-content">
            @if(!empty($profile->email)) <div style="margin-bottom: 6px"><strong>Email:</strong><br>{{ $profile->email }}</div> @endif
            @if(!empty($profile->phone)) <div style="margin-bottom: 6px"><strong>Phone:</strong><br>{{ $profile->phone }}</div> @endif
            @if(!empty($profile->linkedin_url)) <div style="margin-bottom: 6px"><strong>LinkedIn:</strong><br><span style="font-size: 8pt">{{ $profile->linkedin_url }}</span></div> @endif
            @if(!empty($profile->github_url)) <div style="margin-bottom: 6px"><strong>GitHub:</strong><br><span style="font-size: 8pt">{{ $profile->github_url }}</span></div> @endif
        </div>

        @if(!empty($profile->bio))
            <h2 class="sidebar-title">Profile</h2>
            <div class="sidebar-content" style="text-align: justify;">{{ $profile->bio }}</div>
        @endif
        
        @if(count($skills) > 0)
            <h2 class="sidebar-title">Expertise</h2>
            @foreach($skills as $category => $categorySkills)
                <div class="skill-category">{{ $category }}</div>
                <div class="skill-list" style="margin-bottom: 20px;">
                    @foreach($categorySkills as $skill)
                        <div style="margin-bottom: 5px; clear: both;">
                            <span style="display: block; float: left; width: 62%;">{{ $skill->name }}</span>
                            <span style="display: block; float: right; width: 36%; text-align: right;">
                                <div class="star-container">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="star-dot {{ $i <= ($skill->proficiency ?? 3) ? 'filled' : 'empty' }}"></div>
                                    @endfor
                                </div>
                            </span>
                            <div style="clear: both;"></div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endif
    </div>

    <div class="main">
        <h1 class="name">{{ $profile->name }}</h1>
        <div class="title">{{ $profile->title }}</div>
        
        @if(count($projects) > 0)
            <h2 class="main-title">Professional Experience</h2>
            @foreach($projects as $project)
                <div class="project">
                    <div>
                        <span class="project-header">{{ $project->name }}</span>
                        @if(!empty($project->url))
                            <span class="project-url">{{ $project->url }}</span>
                        @endif
                    </div>
                    @if(!empty($project->stack))
                        <div class="project-stack">{{ implode(' • ', (array) $project->stack) }}</div>
                    @endif
                    @if(!empty($project->description))
                        <div class="project-desc">{{ $project->description }}</div>
                    @endif
                </div>
            @endforeach
        @endif
        
        @if(count($educations) > 0)
            <h2 class="main-title" style="margin-top: 30px;">Education & Certifications</h2>
            @foreach($educations as $edu)
                <div class="edu-item">
                    <div>
                        <span class="project-header">{{ $edu->degree }}</span>
                        @if(!empty($edu->year))
                            <span class="project-url" style="color: #64748b;">{{ $edu->year }}</span>
                        @endif
                    </div>
                    @if(!empty($edu->school))
                        <div style="font-size: 10pt; color: #475569; margin-top: 2px;">{{ $edu->school }}</div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</body>
</html>
