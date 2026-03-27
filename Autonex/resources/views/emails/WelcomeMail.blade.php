<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AutoNex</title>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <div style="display:none; font-size:1px; color:#f3f4f6; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;">
        Welcome to AutoNex. Your account is ready.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f4f6; margin:0; padding:24px 0;">
        <tr>
            <td align="center" style="padding:0 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background-color:#ffffff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="padding:24px 28px; background:linear-gradient(135deg, #991b1b 0%, #dc2626 100%);">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color:#ffffff; font-size:24px; line-height:1.2; font-weight:700;">
                                        AutoNex
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top:8px; color:#fee2e2; font-size:14px; line-height:1.4;">
                                        Welcome to your automotive hub
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 28px 20px 28px; color:#111827; font-size:16px; line-height:1.7;">
                            <p style="margin:0 0 14px 0;">Hi {{ $userName }},</p>
                            <p style="margin:0 0 14px 0;">
                                Thank you for creating your <strong>AutoNex</strong> account. You're all set!
                            </p>
                            <p style="margin:0 0 24px 0; color:#4b5563;">
                                You can now manage your cars, book appointments, and browse the marketplace from your dashboard.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0;">
                                <tr>
                                    <td align="center" style="border-radius:8px; background-color:#dc2626;">
                                        <a href="{{ $appUrl }}" target="_blank" rel="noopener"
                                           style="display:inline-block; padding:12px 22px; color:#ffffff; text-decoration:none; font-size:15px; font-weight:700; line-height:1;">
                                            Open AutoNex
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 28px 24px 28px; border-top:1px solid #e5e7eb; color:#6b7280; font-size:13px; line-height:1.5; text-align:left;">
                            If you did not create this account, please ignore this email.
                            <br><br>
                            © {{ date('Y') }} AutoNex. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>