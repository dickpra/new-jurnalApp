<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>SustainScript Email Verification</title>
</head>
<body style="background-color: #f8fafc; font-family: 'Helvetica Neue', Arial, sans-serif; -webkit-font-smoothing: antialiased; margin: 0; padding: 40px 0; color: #1e293b; line-height: 1.6;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f8fafc;">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; max-width: 580px; padding: 10px; width: 580px; margin: 0 auto;">
                
                <div class="content" style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">

                    <table role="presentation" class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%; overflow: hidden;">

                        <tr>
                            <td style="background-color: #0f172a; padding: 30px; text-align: center;">
                                <h1 style="color: #ffffff; font-size: 24px; font-weight: bold; margin: 0; letter-spacing: 2px;">SUSTAINSCRIPT</h1>
                                <p style="color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-top: 5px; margin-bottom: 0;">Journal Publishing Platform</p>
                            </td>
                        </tr>

                        <tr>
                            <td class="wrapper" style="font-family: sans-serif; font-size: 15px; vertical-align: top; box-sizing: border-box; padding: 40px 30px;">
                                
                                <p style="font-family: sans-serif; font-size: 16px; font-weight: normal; margin: 0; margin-bottom: 20px;">
                                    Hello, <strong>{{ $user->name }}</strong>,
                                </p>
                                
                                <p style="font-family: sans-serif; font-size: 15px; font-weight: normal; margin: 0; margin-bottom: 25px; color: #475569;">
                                    Thank you for registering on our scientific journal platform. To secure your account and proceed with your manuscript submission, please verify your email address by clicking the button below:
                                </p>

                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; box-sizing: border-box; width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td align="center" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 25px;">
                                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; border-radius: 4px; text-align: center; background-color: #4338ca;">
                                                                <a href="{{ $url }}" target="_blank" style="border: solid 1px #4338ca; border-radius: 4px; box-sizing: border-box; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-decoration: none; text-transform: capitalize; background-color: #4338ca; border-color: #4338ca; color: #ffffff;">
                                                                    Verify My Email Address
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 15px; color: #64748b;">
                                    If you did not create an account on SustainScript, you can safely ignore this email.
                                </p>
                                
                                <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; color: #64748b;">
                                    Best regards,<br>
                                    <strong>The SustainScript Editorial Team</strong>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <div class="footer" style="clear: both; margin-top: 10px; text-align: center; width: 100%;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                            <tr>
                                <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; color: #94a3b8; font-size: 12px; text-align: center;">
                                    <span class="apple-link" style="color: #94a3b8; font-size: 12px; text-align: center;">© {{ date('Y') }} SustainScript. All rights reserved.</span>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </td>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        </tr>
    </table>
</body>
</html>