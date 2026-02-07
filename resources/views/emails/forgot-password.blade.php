<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password</title>
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
                                Reset Password
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px; color:#333333;">
                            <p style="font-size:16px; margin:0 0 15px;">
                                Hello ,
                            </p>

                            <p style="font-size:14px; line-height:1.6; margin:0 0 20px;">
                                You are receiving this email because we received a password reset request
                                for your account.
                            </p>
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $data['link'] }}"
                                            style="background-color:#0f3d2e;
                                              color:#ffffff;
                                              text-decoration:none;
                                              padding:12px 24px;
                                              border-radius:4px;
                                              font-size:16px;
                                              display:inline-block;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:14px; line-height:1.6; margin:20px 0 0;">
                                This password reset link will expire in <strong>60 minutes</strong>.
                            </p>

                            <p style="font-size:14px; line-height:1.6; margin:15px 0 0;">
                                If you did not request a password reset, no further action is required.
                            </p>

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
