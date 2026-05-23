<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume - Modern</title>
    <style>
        @page { margin: 0; size: a4 portrait; }
        body { font-family: 'Inter', 'Helvetica', 'Arial', sans-serif; font-size: 10pt; color: #e2e8f0; margin: 0; padding: 0; background-color: #0f172a; }
        
        /* Use table for header so dompdf handles it right */
        table.header-table { width: 100%; border-collapse: collapse; background-color: #1e293b; border-bottom: 4px solid #8b5cf6; }
        .header-text { padding: 40px 50px; vertical-align: middle; width: 75%; }
        .header-photo { width: 25%; padding: 20px 40px 20px 0; vertical-align: middle; text-align: right; }
        
        .name { font-size: 32pt; font-weight: 800; color: #ffffff; text-transform: uppercase; letter-spacing: -1px; margin: 0; }
        .title { font-size: 14pt; color: #a78bfa; font-weight: 600; margin-top: 5px; }
        
        table.container { width: 100%; border-collapse: collapse; }
        .sidebar { width: 33%; background-color: #1e293b; color: #cbd5e1; padding: 40px 30px; vertical-align: top; }
        .main { width: 67%; padding: 40px 50px; background-color: #0f172a; vertical-align: top; }
        
        h2.section-title { font-size: 11pt; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; color: #8b5cf6; margin-bottom: 20px; margin-top: 0; border-left: 4px solid #8b5cf6; padding-left: 15px; }
        
        .content-item { margin-bottom: 30px; }
        .item-header { font-size: 12pt; font-weight: 700; color: #ffffff; }
        .item-sub { font-size: 9pt; color: #a78bfa; font-weight: 600; margin-top: 3px; }
        .item-desc { font-size: 10pt; line-height: 1.7; color: #94a3b8; margin-top: 10px; text-align: justify; }
        
        .skill-item { margin-bottom: 12px; }
        .skill-name { font-size: 9pt; font-weight: 600; color: #ffffff; margin-bottom: 4px; }
        .skill-bar { height: 6px; background-color: #334155; border-radius: 3px; }
        .skill-progress { height: 6px; background-color: #8b5cf6; border-radius: 3px; }
        
        .contact-item { font-size: 9pt; margin-bottom: 15px; color: #94a3b8; }
        .contact-item strong { color: #ffffff; display: block; font-size: 8pt; text-transform: uppercase; margin-bottom: 3px; }

        .photo-circle { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #8b5cf6; }
    </style>
</head>
<body>
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="header-text">
                <div class="name">{{ $profile->name }}</div>
                <div class="title">{{ $profile->title }}</div>
            </td>
            <td class="header-photo">
                @if(!empty($profile->photo))
                    <img src="{{ public_path('storage/' . $profile->photo) }}" class="photo-circle" style="display:inline-block;">
                @endif
            </td>
        </tr>
    </table>

    <table class="container" cellspacing="0" cellpadding="0">
        <tr>
            <td class="sidebar">
                @if(($showQr ?? true) && isset($portfolioUrl))
                    <div style="margin-bottom: 30px;">
                        <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(90)->margin(0)->generate($portfolioUrl)) !!}" style="width: 90px; height: 90px; background: white; padding: 6px; border-radius: 8px;" />
                        <div style="font-size: 8pt; margin-top: 10px; color: #8b5cf6; font-weight: bold;">SCAN PORTFOLIO</div>
                    </div>
                @endif

                <h2 class="section-title">Contact</h2>
                <div class="contact-item"><strong>Email</strong>{{ $profile->email }}</div>
                <div class="contact-item"><strong>Phone</strong>{{ $profile->phone }}</div>
                @if($profile->linkedin_url) <div class="contact-item"><strong>LinkedIn</strong>{{ $profile->linkedin_url }}</div> @endif
                @if($profile->github_url) <div class="contact-item"><strong>GitHub</strong>{{ $profile->github_url }}</div> @endif

                @if(count($skills) > 0)
                    <h2 class="section-title" style="margin-top: 40px;">Expertise</h2>
                    @foreach($skills as $category => $categorySkills)
                        <div style="font-size: 8pt; font-weight: 800; color: #8b5cf6; margin-bottom: 10px; text-transform: uppercase;">{{ $category }}</div>
                        @foreach($categorySkills as $skill)
                            <div class="skill-item">
                                <div class="skill-name">{{ $skill->name }}</div>
                                <div class="skill-bar">
                                    <div class="skill-progress" style="width: {{ ($skill->proficiency ?? 3) * 20 }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                @endif
            </td>
            <td class="main">
                @if($profile->bio)
                    <h2 class="section-title">Executive Summary</h2>
                    <div class="item-desc" style="margin-bottom: 40px; font-size: 11pt; color: #e2e8f0;">{{ $profile->bio }}</div>
                @endif

                @if(count($projects) > 0)
                    <h2 class="section-title">Experience & Projects</h2>
                    @foreach($projects as $project)
                        <div class="content-item">
                            <div class="item-header">{{ $project->name }}</div>
                            @if($project->stack)
                                <div class="item-sub">{{ implode(' • ', (array)$project->stack) }}</div>
                            @endif
                            <div class="item-desc">{{ $project->description }}</div>
                        </div>
                    @endforeach
                @endif

                @if(count($educations) > 0)
                    <h2 class="section-title" style="margin-top: 40px;">Education</h2>
                    @foreach($educations as $edu)
                        <div class="content-item">
                            <div class="item-header">{{ $edu->degree }}</div>
                            <div class="item-sub" style="color: #ffffff;">{{ $edu->school }} | {{ $edu->year }}</div>
                        </div>
                    @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
