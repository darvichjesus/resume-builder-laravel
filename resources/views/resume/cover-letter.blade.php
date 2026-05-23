<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cover Letter</title>
    <style>
        @page { margin: 2.5cm 2.5cm; size: a4 portrait; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11pt; color: #1e293b; line-height: 1.8; }
        .header { margin-bottom: 50px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px;}
        h1 { font-size: 24pt; font-weight: bold; margin: 0; color: #0f172a;}
        .contact { color: #475569; margin-top: 5px; font-size: 10pt;}
        .date { margin-bottom: 30px; color: #475569; }
        p { text-align: justify; margin-bottom: 20px; }
        .signature { margin-top: 50px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $profile->name }}</h1>
        <div class="contact">
            @if(!empty($profile->email)) {{ $profile->email }} • @endif 
            @if(!empty($profile->phone)) {{ $profile->phone }} @endif
        </div>
    </div>
    
    <div class="date">{{ date('F j, Y') }}</div>
    
    <p>{{ $content['greeting'] }}</p>
    <p>{{ $content['para1'] }}</p>
    @if(!empty($content['paraBio']))
        <p>{{ $content['paraBio'] }}</p>
    @endif
    <p>{{ $content['para2'] }}</p>
    <p>{{ $content['para3'] }}</p>
    
    <div class="signature">
        <p style="margin-bottom: 40px;">{{ $content['closing'] }}</p>
        <p style="margin-bottom: 5px;">{{ $content['signoff'] }}</p>
        <strong>{{ $profile->name }}</strong>
    </div>
</body>
</html>
