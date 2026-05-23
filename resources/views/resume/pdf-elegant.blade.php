<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1.5cm 2cm; size: a4 portrait; }
        body { font-family: "Georgia", serif; font-size: 10pt; color: #374151; margin: 0; padding: 0; }

        table.header-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .header-center { text-align: center; vertical-align: middle; }
        .header-side { width: 80px; vertical-align: middle; }

        h1 { font-size: 26pt; font-weight: normal; margin: 0; color: #111827;}
        .title { font-size: 12pt; color: #6b7280; font-style: italic; margin-top: 5px; }
        .contact { font-size: 9pt; color: #4b5563; margin-top: 10px; }
        .contact span { margin: 0 8px; border-right: 1px solid #d1d5db; padding-right: 16px; }
        .contact span:last-child { border-right: none; }
        
        h2.section-title { font-size: 14pt; color: #111827; font-weight: normal; margin-top: 30px; margin-bottom: 10px; text-align: center; text-transform: uppercase; letter-spacing: 2px;}
        .section-separator { width: 40px; height: 2px; background: #9ca3af; margin: 0 auto 20px auto; }
        
        .bio { text-align: justify; line-height: 1.7; color: #4b5563; }
        
        table.item { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        td.item-left { text-align: left; font-weight: bold; color: #111827; font-size: 11pt;}
        td.item-right { text-align: right; color: #6b7280; font-style: italic;}
        .item-sub { color: #6b7280; margin-bottom: 8px;}
        .item-desc { text-align: justify; margin-bottom: 20px; line-height: 1.6;}

        .star-container { display: inline-block; white-space: nowrap; vertical-align: middle; margin-left: 3px; line-height: 1; }
        .star-dot { display: inline-block; width: 5px; height: 5px; border-radius: 50%; margin-left: 2px; border: 1px solid #374151; }
        .star-dot.filled { background-color: #374151; }
        .star-dot.empty { background-color: transparent; }

        .photo-circle { width: 75px; height: 75px; border-radius: 50%; object-fit: cover; border: 1px solid #d1d5db; }
    </style>
</head>
<body>
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="header-side">
                @if(!empty($profile->photo))
                    <img src="{{ public_path('storage/' . $profile->photo) }}" class="photo-circle">
                @endif
            </td>
            <td class="header-center">
                <h1>{{ $profile->name }}</h1>
                <div class="title">{{ $profile->title }}</div>
                <div class="contact">
                    @if(!empty($profile->email)) <span>{{ $profile->email }}</span> @endif
                    @if(!empty($profile->phone)) <span>{{ $profile->phone }}</span> @endif
                </div>
            </td>
            <td class="header-side" style="text-align: right;">
                @if(($showQr ?? true) && isset($portfolioUrl))
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(70)->margin(0)->color(55, 65, 81)->generate($portfolioUrl)) !!}" style="width: 70px; height: 70px;" />
                @endif
            </td>
        </tr>
    </table>
    
    @if(!empty($profile->bio))
        <div class="bio">{{ $profile->bio }}</div>
    @endif

    @if(count($educations) > 0)
        <h2 class="section-title">Education</h2>
        <div class="section-separator"></div>
        @foreach($educations as $edu)
            <table class="item">
                <tr>
                    <td class="item-left">{{ $edu->degree }}</td>
                    <td class="item-right">{{ $edu->year }}</td>
                </tr>
            </table>
            <div class="item-sub">{{ $edu->school }}</div>
        @endforeach
    @endif

    @if(count($projects) > 0)
        <h2 class="section-title">Experience</h2>
        <div class="section-separator"></div>
        @foreach($projects as $project)
            <table class="item">
                <tr>
                    <td class="item-left">{{ $project->name }}</td>
                    <td class="item-right">@if(!empty($project->url)) <a href="{{ $project->url }}" style="color: #6b7280;">{{ $project->url }}</a> @endif</td>
                </tr>
            </table>
            @if(!empty($project->stack))
                <div class="item-sub">{{ implode(' • ', (array) $project->stack) }}</div>
            @endif
            @if(!empty($project->description))
                <div class="item-desc">{{ $project->description }}</div>
            @endif
        @endforeach
    @endif

    @if(count($skills) > 0)
        <h2 class="section-title">Skills</h2>
        <div class="section-separator"></div>
        <div style="text-align: center; line-height: 2;">
            @foreach($skills as $category => $categorySkills)
                <strong>{{ $category }}:</strong>
                @foreach($categorySkills as $skill)
                    {{ $skill->name }}
                    <div class="star-container">
                        @for($i = 1; $i <= 5; $i++)
                            <div class="star-dot {{ $i <= ($skill->proficiency ?? 3) ? 'filled' : 'empty' }}"></div>
                        @endfor
                    </div>
                    @if(!$loop->last),&nbsp;@endif
                @endforeach
                <br>
            @endforeach
        </div>
    @endif
</body>
</html>
