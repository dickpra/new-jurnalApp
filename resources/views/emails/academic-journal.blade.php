<!DOCTYPE html>
<html>
<head>
    <style>
        /* Desain Klasik ala Jurnal Internasional */
        body { 
            background-color: #e7e5e4; 
            font-family: 'Georgia', 'Times New Roman', serif; 
            color: #1c1917; 
            margin: 0; 
            padding: 40px 0; 
        }
        .container { 
            max-width: 650px; 
            margin: 0 auto; 
            background: #ffffff; 
            border: 1px solid #d6d3d1; 
            border-top: 8px solid #1c1917; 
            padding: 50px 40px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }
        .header { 
            text-align: center; 
            border-bottom: 2px solid #1c1917; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        .journal-name { 
            font-size: 26px; 
            font-weight: bold; 
            text-transform: uppercase; 
            letter-spacing: 3px; 
        }
        .decision { 
            font-size: 12px; 
            color: #57534e; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            margin-top: 8px; 
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }
        /* Pewarnaan Status Dinamis */
        .status-accepted { color: #15803d; }
        .status-rejected { color: #b91c1c; }
        .status-revision { color: #b45309; }
        .status-paid { color: #047857; } /* Hijau Emerald */

        .content { line-height: 1.8; font-size: 16px; text-align: justify; }
        
        .box { 
            background-color: #fafaf9; 
            border: 1px solid #e7e5e4;
            border-left: 4px solid #1c1917; 
            padding: 25px; 
            margin: 30px 0; 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 14px; 
        }
        
        .button { 
            display: inline-block; 
            padding: 14px 28px; 
            background-color: #1c1917; 
            color: #ffffff !important; 
            text-decoration: none; 
            font-family: 'Arial', sans-serif; 
            text-transform: uppercase; 
            letter-spacing: 1px; 
            font-size: 12px; 
            font-weight: bold;
            margin-top: 20px; 
        }
        
        .footer { 
            border-top: 1px solid #e7e5e4; 
            margin-top: 50px; 
            padding-top: 20px; 
            text-align: center; 
            font-size: 11px; 
            color: #a8a29e; 
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="journal-name">{{ $submission->journalTheme->name }}</div>
            <div class="decision status-{{ strtolower($decisionType) }}">
                Official Editorial Decision: {{ $decisionType }}
            </div>
        </div>
        
        <div class="content">
            <p>Dear <strong>{{ $submission->author->name }}</strong>,</p>
            
            <p>{!! $openingText !!}</p>
            
            <p><strong>Manuscript Title:</strong> <em>"{{ $submission->title }}"</em></p>

            <div class="box">
                <strong style="font-family: 'Georgia', serif; text-transform: uppercase; font-size: 12px;">Editor & Reviewer Notes:</strong><br><br>
                {!! nl2br(e($customMessage)) !!}
            </div>

            <p>Please log in to your Author Workspace to view detailed instructions and proceed with the necessary steps.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('author.dashboard') }}" class="button">Access Author Workspace</a>
            </div>

            <p style="margin-top: 40px;">
                Sincerely,<br>
                <strong>Editorial Board</strong><br>
                {{ $submission->journalTheme->name }}
            </p>
        </div>
        
        <div class="footer">
            {{-- &copy; {{ date('Y') }} {{ $submission->journalTheme->name }}. All rights reserved.<br> --}}
            &copy; {{ date('Y') }} SustainSUN. All rights reserved.<br>
            This is an automated notification. Please do not reply to this email.
        </div>
    </div>
</body>
</html>