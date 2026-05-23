<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creative Resume</title>
    <style>
        @page { margin: 0; size: a4 portrait; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #334155; margin:0; padding:0; background: #ffffff;}
        
        /* Header using a table so dompdf renders correctly */
        .header-table { width: 100%; border-collapse: collapse; background-color: #8b5cf6; }
        .header-left { padding: 30px 40px; vertical-align: middle; }
        .header-right { width: 100px; padding: 15px; vertical-align: middle; text-align: center; }

        h1 { font-size: 28pt; font-weight: bold; margin: 0; text-transform: uppercase; letter-spacing: 2px; color: #ffffff;}
        .title { font-size: 13pt; margin-top: 5px; font-weight: bold; color: #ddd6fe; letter-spacing: 1px;}
        
        table.layout { width: 100%; border-collapse: collapse; }
        
        h2.section-title { font-size: 12pt; text-transform: uppercase; letter-spacing: 1px; color: #7c3aed; font-weight: bold; margin-bottom: 15px; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px;}
        h2.section-title.first { margin-top: 0; }
        
        .bio { line-height: 1.4; text-align: justify; margin-bottom: 15px; font-size: 9.5pt;}
        .contact div, .contact a { margin: 5px 0; color: #475569; font-weight: bold; text-decoration: none; display:block; word-wrap: break-word;}
        .skill-category { font-weight: bold; color: #6d28d9; margin-bottom: 3px; font-size: 9pt; margin-top: 10px; }
        
        .star-container { display: inline-block; white-space: nowrap; vertical-align: middle; margin-left: 5px; }
        .star-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; margin-left: 2px; }
        .star-dot.filled { background-color: #8b5cf6; }
        .star-dot.empty { background-color: #e5e7eb; border: 1px solid #d1d5db; }
        
        .project { margin-bottom: 15px; }
        .project-header { font-weight: bold; font-size: 10.5pt; color: #0f172a; }
        .project-stack { font-size: 8pt; color: #7c3aed; font-weight: bold; margin-top: 2px; letter-spacing:0.5px;}
        .project-desc { margin-top: 3px; text-align: justify; color: #4b5563; line-height: 1.4; word-wrap: break-word; font-size: 9pt; }

        .photo-circle { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255,255,255,0.5); }
        .qr-white-bg { background: white; padding: 5px; border-radius: 6px; }
    </style>
</head>
<body>
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="header-left">
                <h1>{{ $profile->name }}</h1>
                <div class="title">{{ $profile->title }}</div>
            </td>
            <td class="header-right">
                @if(!empty($profile->photo))
                    <img src="{{ public_path('storage/' . $profile->photo) }}" class="photo-circle" style="display:block; margin: 0 auto 8px auto;">
                @endif
                @if(($showQr ?? true) && isset($portfolioUrl))
                    <div class="qr-white-bg" style="display:inline-block;">
                        <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->margin(0)->generate($portfolioUrl)) !!}" style="width: 70px; height: 70px; display:block;" />
                    </div>
                @endif
            </td>
        </tr>
    </table>
    
    <table class="layout" cellspacing="0" cellpadding="0">
        <tr>
            <td width="65%" style="padding: 35px 45px; vertical-align: top; background-color: #ffffff;">
                @if(!empty($profile->bio))
                    <h2 class="section-title first">About Me</h2>
                    <div class="bio">{{ $profile->bio }}</div>
                @endif
                
                @if(count($projects) > 0)
                    <h2 class="section-title">Experience</h2>
                    @foreach($projects as $project)
                        <div class="project">
                            <table style="width: 100%; border: none;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="project-header">{{ $project->name }}</td>
                                    @if(!empty($project->url))
                                        <td style="text-align: right;"><a href="{{ $project->url }}" style="font-size: 9pt; color: #8b5cf6; text-decoration: none;">{{ $project->url }}</a></td>
                                    @endif
                                </tr>
                            </table>
                            @if(!empty($project->stack))
                                <div><span class="project-stack">{{ implode(' • ', (array) $project->stack) }}</span></div>
                            @endif
                            @if(!empty($project->description))
                                <div class="project-desc">{{ $project->description }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif
                
                @if(count($educations) > 0)
                    <h2 class="section-title" style="margin-top: 30px;">Education</h2>
                    @foreach($educations as $edu)
                        <div class="project" style="margin-bottom: 20px;">
                            <table style="width: 100%; border: none;" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="project-header">{{ $edu->degree }}</td>
                                    @if(!empty($edu->year))
                                        <td style="text-align: right; color: #64748b; font-size: 9pt;">{{ $edu->year }}</td>
                                    @endif
                                </tr>
                            </table>
                            @if(!empty($edu->school))
                                <div class="project-stack" style="text-transform: none; color: #475569;">{{ $edu->school }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </td>
            
            <td width="35%" style="padding: 35px 25px; vertical-align: top; background-color: #f8fafc;">
                <h2 class="section-title first">Contact</h2>
                <div class="contact">
                    @if(!empty($profile->email)) <div>{{ $profile->email }}</div> @endif
                    @if(!empty($profile->phone)) <div>{{ $profile->phone }}</div> @endif
                    @if(!empty($profile->linkedin_url)) <div><a href="{{ $profile->linkedin_url }}">{{ $profile->linkedin_url }}</a></div> @endif
                    @if(!empty($profile->github_url)) <div><a href="{{ $profile->github_url }}">{{ $profile->github_url }}</a></div> @endif
                </div>
                
                @if(count($skills) > 0)
                    <h2 class="section-title" style="margin-top: 30px;">Skills</h2>
                    @foreach($skills as $category => $categorySkills)
                        <div class="skill-category">{{ $category }}</div>
                        @foreach($categorySkills as $skill)
                            <div style="margin-bottom: 6px; font-size: 9pt;">
                                <span style="color: #374151;">{{ $skill->name }}</span>
                                <span style="float: right;">
                                    <div class="star-container">
                                        @for($i = 1; $i <= 5; $i++)
                                            <div class="star-dot {{ $i <= ($skill->proficiency ?? 3) ? 'filled' : 'empty' }}"></div>
                                        @endfor
                                    </div>
                                </span>
                                <div style="clear:both;"></div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
