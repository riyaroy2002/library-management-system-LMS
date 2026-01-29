<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reply from Library Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:20px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0"
                   style="background-color:#ffffff; border-radius:6px; overflow:hidden;">

                <tr>
                    <td style="background-color:#0f3d2e; padding:20px; text-align:center;">
                        <h1 style="color:#ffffff; margin:0; font-size:22px;">
                            Reply from Library Management System
                        </h1>
                    </td>
                </tr>

                <tr>
                    <td style="padding:30px; color:#333333;">

                        <p style="font-size:16px; margin:0 0 15px;">
                            Hello <strong>{{ $data['name'] }}</strong>,
                        </p>

                        <p style="font-size:14px; line-height:1.6; margin:0 0 20px;">
                            Thank you for reaching out to us. Below is the response to your inquiry.
                        </p>


                        <div style="background-color:#f8f9fa; padding:15px; border-left:4px solid #0f3d2e; margin-bottom:20px;">
                            <p style="margin:0; font-size:13px; color:#555;">
                                <strong>Your Message:</strong>
                            </p>
                            <p style="margin:8px 0 0; font-size:14px; color:#333;">
                                {{ $data['message'] }}
                            </p>
                        </div>

                        <div style="background-color:#eef6f3; padding:15px; border-left:4px solid #198754;">
                            <p style="margin:0; font-size:13px; color:#555;">
                                <strong>Our Reply:</strong>
                            </p>
                            <p style="margin:8px 0 0; font-size:14px; color:#333;">
                                {{ $data['replyBack'] }}
                            </p>
                        </div>

                        <p style="font-size:14px; margin-top:30px;">
                            Regards,<br>
                            <strong>Library Management System</strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#f4f6f8; padding:15px; text-align:center;">
                        <p style="font-size:12px; color:#777777; margin:0;">
                            © {{ date('Y') }} Library Management System. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
