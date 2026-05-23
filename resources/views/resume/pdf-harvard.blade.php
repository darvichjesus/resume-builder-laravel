<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 2cm 2.5cm; size: a4 portrait; }
        body { font-family: "Times New Roman", Times, serif; font-size: 11pt; color: #000; margin: 0; padding: 0; }
        
        /* Header layout using table for correct dompdf rendering */
        table.header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-side { width: 80px; vertical-align: top; }
        .header-center { text-align: center; vertical-align: middle; }
        .header-side-right { width: 70px; vertical-align: top; text-align: right; }

        h1 { font-size: 20pt; text-transform: uppercase; margin: 0; font-weight: normal; }
        .contact { font-size: 10pt; margin-top: 5px; }
        .contact span { margin: 0 5px; }
        h2.section-title { font-size: 11pt; text-transform: uppercase; border-bottom: 1px solid #000; margin-top: 20px; margin-bottom: 10px; font-weight: bold; padding-bottom: 2px;}
        .bio { text-align: justify; margin-bottom: 15px; line-height: 1.3;}
        table.item { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        td.item-left { text-align: left; font-weight: bold; }
        td.item-right { text-align: right; }
        .item-desc { text-align: justify; margin-bottom: 15px; line-height: 1.3;}
        .skills-table { width: 100%; border-collapse: collapse; }
        .skills-cat { font-weight: bold; width: 150px; vertical-align: top; padding-bottom: 8px;}
        .skills-list { vertical-align: top; padding-bottom: 8px; line-height: 1.6; }
        
        .star-container { display: inline-block; white-space: nowrap; vertical-align: middle; margin-left: 6px; line-height: 1; }
        .star-dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; margin-left: 2px; }
        .star-dot.filled { background-color: #000; }
        .star-dot.empty { background-color: #fff; border: 1px solid #ccc; }

        .photo-circle { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; border: 1px solid #000; }
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
                <div class="contact">
                    @if(!empty($profile->email)) <span>{{ $profile->email }}</span> @endif
                    @if(!empty($profile->phone)) | <span>{{ $profile->phone }}</span> @endif
                    @if(!empty($profile->linkedin_url)) | <span>{{ $profile->linkedin_url }}</span> @endif
                </div>
            </td>
            <td class="header-side-right">
                @if(($showQr ?? true) && isset($portfolioUrl))
                    <img src="data:image/svg+xml;base64,{!! base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(65)->margin(0)->color(0, 0, 0)->generate($portfolioUrl)) !!}" style="width: 65px; height: 65px;" />
                @endif
            </td>
        </tr>
    </table>
    
    @if(!empty($profile->bio))
        <div class="bio">{{ $profile->bio }}</div>
    @endif

    @if(count($educations) > 0)
        <h2 class="section-title">Education</h2>
        @foreach($educations as $edu)
            <table class="item">
                <tr>
                    <td class="item-left">{{ $edu->school }}</td>
                    <td class="item-right">{{ $edu->year }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-style: italic;">{{ $edu->degree }}</td>
                </tr>
            </table>
        @endforeach
    @endif

    @if(count($projects) > 0)
        <h2 class="section-title">Experience & Projects</h2>
        @foreach($projects as $project)
            <table class="item">
                <tr>
                    <td class="item-left">{{ $project->name }}</td>
                    <td class="item-right">@if(!empty($project->url)) {{ $project->url }} @endif</td>
                </tr>
            </table>
            @if(!empty($project->description))
                <div class="item-desc">{{ $project->description }}</div>
            @endif
        @endforeach
    @endif

    @if(count($skills) > 0)
        <h2 class="section-title">Skills & Interests</h2>
        <table class="skills-table">
            @foreach($skills as $category => $categorySkills)
                <tr>
                    <td class="skills-cat">{{ $category }}:</td>
                    <td class="skills-list">
                        @foreach($categorySkills as $skill)
                            {{ $skill->name }}
                            <div class="star-container">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="star-dot {{ $i <= ($skill->proficiency ?? 3) ? 'filled' : 'empty' }}"></div>
                                @endfor
                            </div>
                            @if(!$loop->last), &nbsp;@endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</body>
</html>
