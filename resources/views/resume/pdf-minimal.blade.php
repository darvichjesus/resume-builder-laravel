<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Minimal Resume</title>
    <style>
        @page { margin: 1.5cm 2cm; size: a4 portrait; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #1e293b; line-height: 1.6; }
        
        .header-table { width: 100%; border-collapse: collapse; border-bottom: 1px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 25px; }
        .header-left { vertical-align: middle; }
        .header-right { vertical-align: middle; text-align: right; width: 90px; }
        
        h1 { font-size: 26pt; font-weight: bold; margin: 0; text-transform: uppercase; color: #0f172a; letter-spacing: 2px;}
        .title { font-size: 13pt; color: #64748b; font-weight: bold; margin-top: 5px; }
        .contact { font-size: 9pt; color: #475569; margin-top: 10px; }
        .contact span { margin-right: 15px; }
        
        h2.section-title { font-size: 12pt; text-transform: uppercase; letter-spacing: 1px; color: #0f172a; font-weight: bold; margin-top: 30px; margin-bottom: 15px; }
        
        .bio { text-align: justify; color: #475569; }
        
        .skill-group { margin-bottom: 8px; }
        .skill-cat { font-weight: bold; width: 140px; display: inline-block; color: #334155; vertical-align: top; }
        .skill-items { color: #64748b; display: inline-block; width: 75%; }
        
        .star-container { display: inline-block; white-space: nowrap; vertical-align: middle; margin-left: 4px; line-height: 1; }
        .star-dot { display: inline-block; width: 5px; height: 5px; border-radius: 50%; margin-left: 2px; border: 1px solid #f59e0b; }
        .star-dot.filled { background-color: #f59e0b; }
        .star-dot.empty { background-color: transparent; }

        .project { margin-bottom: 20px; }
        .project-header { font-weight: bold; font-size: 11pt; color: #1e293b; }
        .project-stack { font-size: 9pt; color: #94a3b8; font-style: italic; margin-top: 2px; }
        .project-desc { text-align: justify; margin-top: 5px; color: #475569; }

        .photo-circle { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
        .qr-img { width: 65px; height: 65px; }
    </style>
</head>
<body>
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="header-left">
                <h1>{{ $profile->name }}</h1>
                <div class="title">{{ $profile->title }}</div>
                <div class="contact">
                    @if(!empty($profile->email)) <span>{{ $profile->email }}</span> @endif
                    @if(!empty($profile->phone)) <span>{{ $profile->phone }}</span> @endif
                    @if(!empty($profile->linkedin_url)) <span>LinkedIn</span> @endif
                </div>
            </td>
            <td class="header-right">
                @if(!empty($profile->photo))
                    <img src="{{ public_path('storage/' . $profile->photo) }}" class="photo-circle" style="display:block; margin: 0 0 6px auto;"><br>
                @endif
                @if(($showQr ?? true) && isset($portfolioUrl))
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->margin(0)->generate($portfolioUrl)) !!}" class="qr-img" style="display:block; margin-left: auto;">
                @endif
            </td>
        </tr>
    </table>
    
    @if(!empty($profile->bio))
        <h2 class="section-title">Professional Profile</h2>
        <div class="bio">{{ $profile->bio }}</div>
    @endif
    
    @if(count($skills) > 0)
        <h2 class="section-title">Technical Expertise</h2>
        @foreach($skills as $category => $categorySkills)
            <div class="skill-group">
                <span class="skill-cat">{{ $category }}:</span>
                <span class="skill-items">
                    @foreach($categorySkills as $skill)
                        {{ $skill->name }}
                        <div class="star-container">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="star-dot {{ $i <= ($skill->proficiency ?? 3) ? 'filled' : 'empty' }}"></div>
                            @endfor
                        </div>
                        @if(!$loop->last)&nbsp;&nbsp;@endif
                    @endforeach
                </span>
            </div>
        @endforeach
    @endif
    
    @if(count($projects) > 0)
        <h2 class="section-title">Experience & Projects</h2>
        @foreach($projects as $project)
            <div class="project">
                <table style="width: 100%; border: none;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="project-header">{{ $project->name }}</td>
                        @if(!empty($project->url))
                            <td style="text-align: right; font-size: 9pt; color: #2563eb;">{{ $project->url }}</td>
                        @endif
                    </tr>
                </table>
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
        <h2 class="section-title" style="margin-top: 30px;">Education & Certifications</h2>
        @foreach($educations as $edu)
            <div class="project" style="margin-bottom: 15px;">
                <table style="width: 100%; border: none;" cellspacing="0" cellpadding="0">
                    <tr>
                        <td class="project-header">{{ $edu->degree }}</td>
                        @if(!empty($edu->year))
                            <td style="text-align: right; color: #64748b; font-size: 9pt;">{{ $edu->year }}</td>
                        @endif
                    </tr>
                </table>
                @if(!empty($edu->school))
                    <div style="font-size: 9pt; color: #475569; margin-top: 3px;">{{ $edu->school }}</div>
                @endif
            </div>
        @endforeach
    @endif
</body>
</html>
