<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume - Professional Compact</title>
    <style>
        @page { margin: 1cm; size: a4 portrait; }
        body { font-family: 'Helvetica', sans-serif; font-size: 9pt; color: #1f2937; line-height: 1.4; margin: 0; padding: 0; }
        
        table.header-table { width: 100%; border-collapse: collapse; border-bottom: 2px solid #1f2937; padding-bottom: 10px; margin-bottom: 15px; }
        .header-main { vertical-align: top; }
        .header-right { width: 130px; vertical-align: top; text-align: right; }

        .name { font-size: 22pt; font-weight: bold; color: #111827; margin: 0; }
        .title { font-size: 11pt; color: #4b5563; font-weight: bold; margin-top: 2px; text-transform: uppercase; letter-spacing: 1px; }
        
        .contact-info { margin-top: 8px; font-size: 8.5pt; color: #4b5563; }
        .contact-info span { margin-right: 15px; }
        
        h2 { font-size: 10.5pt; text-transform: uppercase; border-bottom: 1px solid #e5e7eb; padding-bottom: 3px; margin-top: 15px; margin-bottom: 8px; color: #111827; }
        
        .section { margin-bottom: 12px; }
        .item-title { font-weight: bold; font-size: 10pt; color: #111827; }
        .item-meta { color: #6b7280; font-size: 8.5pt; font-style: italic; margin-bottom: 3px; }
        .item-desc { text-align: justify; color: #374151; }
        
        .skills-grid { width: 100%; border-collapse: collapse; }
        .skill-cat { font-weight: bold; width: 120px; vertical-align: top; padding: 4px 0; color: #4b5563; font-size: 8.5pt; text-transform: uppercase; }
        .skill-vals { vertical-align: top; padding: 4px 0; }

        .photo-img { width: 65px; height: 65px; border-radius: 4px; object-fit: cover; border: 1px solid #e5e7eb; display: block; margin-bottom: 6px; margin-left: auto; }
        .qr-img { width: 55px; height: 55px; display: block; margin-left: auto; }

        .star-container { display: inline-block; white-space: nowrap; vertical-align: middle; margin-left: 3px; line-height: 1; }
        .star-dot { display: inline-block; width: 4px; height: 4px; border-radius: 50%; margin-left: 1px; border: 1px solid #374151; }
        .star-dot.filled { background-color: #374151; }
        .star-dot.empty { background-color: transparent; }
    </style>
</head>
<body>
    <table class="header-table" cellspacing="0" cellpadding="8">
        <tr>
            <td class="header-main">
                <div class="name">{{ $profile->name }}</div>
                <div class="title">{{ $profile->title }}</div>
                <div class="contact-info">
                    @if($profile->email) <span><strong>E:</strong> {{ $profile->email }}</span> @endif
                    @if($profile->phone) <span><strong>P:</strong> {{ $profile->phone }}</span> @endif
                    @if($profile->linkedin_url) <span>{{ $profile->linkedin_url }}</span> @endif
                </div>
            </td>
            <td class="header-right">
                @if(!empty($profile->photo))
                    <img src="{{ public_path('storage/' . $profile->photo) }}" class="photo-img">
                @endif
                @if(($showQr ?? true) && isset($portfolioUrl))
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(55)->margin(0)->generate($portfolioUrl)) !!}" class="qr-img" />
                @endif
            </td>
        </tr>
    </table>

    @if($profile->bio)
        <div class="section">
            <h2>Professional Profile</h2>
            <div class="item-desc">{{ $profile->bio }}</div>
        </div>
    @endif

    @if(count($projects) > 0)
        <div class="section">
            <h2>Professional Experience</h2>
            @foreach($projects as $project)
                <div style="margin-bottom: 8px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span class="item-title">{{ $project->name }}</span>
                        @if($project->url) <span style="font-size: 8pt; color: #2563eb;">{{ $project->url }}</span> @endif
                    </div>
                    @if($project->stack)
                        <div class="item-meta">{{ implode(' | ', (array)$project->stack) }}</div>
                    @endif
                    <div class="item-desc">{{ $project->description }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(count($skills) > 0)
        <div class="section">
            <h2>Technical Skills & Expertise</h2>
            <table class="skills-grid">
                @foreach($skills as $category => $categorySkills)
                <tr>
                    <td class="skill-cat">{{ strtoupper($category) }}</td>
                    <td class="skill-vals">
                        @foreach($categorySkills as $skill)
                            <span style="display: inline-block; margin-right: 14px;">
                                {{ $skill->name }}
                                <div class="star-container">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="star-dot {{ $i <= ($skill->proficiency ?? 3) ? 'filled' : 'empty' }}"></div>
                                    @endfor
                                </div>
                            </span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    @endif

    @if(count($educations) > 0)
        <div class="section">
            <h2>Education & Academic Data</h2>
            @foreach($educations as $edu)
                <div style="margin-bottom: 5px;">
                    <span class="item-title">{{ $edu->degree }}</span> — <span style="color: #4b5563;">{{ $edu->school }}</span>
                    <span style="float: right; color: #6b7280;">{{ $edu->year }}</span>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
