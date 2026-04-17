<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Journal Notification</title>
    <style>
        body {
            background-color: #f4f4f5;
            margin: 0;
            padding: 40px 0;
            font-family: 'Georgia', 'Times New Roman', serif;
        }
        .container {
            max-w-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-top: 5px solid #1c1917; /* Garis atas elegan */
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            padding: 40px 40px 20px 40px;
            border-bottom: 1px solid #e7e5e4;
        }
        .header h1 {
            margin: 0;
            color: #1c1917;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #78716c;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 40px;
            color: #292524;
            line-height: 1.8;
            font-size: 15px;
            text-align: justify;
        }
        .footer {
            background-color: #fafaf9;
            padding: 30px 40px;
            text-align: center;
            color: #78716c;
            font-size: 12px;
            border-top: 1px solid #e7e5e4;
        }
        /* Format untuk garis pemisah catatan reviewer/editor */
        hr {
            border: 0;
            border-top: 1px dashed #d6d3d1;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="container">
            <div class="header">
                <h1>Editorial Office</h1>
                <p>Official Notification Letter</p>
            </div>
            
            <div class="content">
                {!! nl2br(e($bodyText)) !!}
            </div>

            <div class="footer">
                <p>This is an automated message from the Journal Management System. Please do not reply directly to this email.</p>
                <p>&copy; {{ date('Y') }} SustainSUN. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>