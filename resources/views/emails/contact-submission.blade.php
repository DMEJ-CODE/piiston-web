<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background: #1a3c6c; padding: 20px; text-align: center;">
                            <h1 style="color: #fff; margin: 0; font-size: 22px;">Piiston</h1>
                            <p style="color: rgba(255,255,255,0.7); margin: 4px 0 0; font-size: 13px;">New Contact Form Submission</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px;">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                        <strong style="color: #333; display: inline-block; width: 100px;">Name:</strong>
                                        <span style="color: #555;">{{ $submission->name }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                        <strong style="color: #333; display: inline-block; width: 100px;">Email:</strong>
                                        <a href="mailto:{{ $submission->email }}" style="color: #1a3c6c; text-decoration: none;">{{ $submission->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                        <strong style="color: #333; display: inline-block; width: 100px;">Role:</strong>
                                        <span style="color: #555;">{{ $submission->role ?: 'Not specified' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                        <strong style="color: #333; display: inline-block; width: 100px;">Message:</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0; color: #555; line-height: 1.6; white-space: pre-wrap;">{{ $submission->message }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background: #f9f9f9; padding: 16px; text-align: center; font-size: 12px; color: #999;">
                            Submitted on {{ $submission->created_at->format('Y-m-d H:i') }} @if($submission->ip_address) | IP: {{ $submission->ip_address }} @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
