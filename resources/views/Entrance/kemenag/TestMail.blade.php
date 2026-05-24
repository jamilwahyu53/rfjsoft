<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>

<body style="margin:0; padding:0; background:#f0f2f5; font-family:Arial, sans-serif;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#f0f2f5; padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="100%" border="0" cellspacing="0" cellpadding="0" 
                       style="max-width: 460px; background:#ffffff; border-radius:10px; padding:30px;">

                    <!-- Title -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="margin:0; font-size:24px; color:#333;">Kode OTP Verifikasi</h2>
                        </td>
                    </tr>

                    <!-- Subtitle -->
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <p style="margin:0; font-size:15px; color:#555;">
                                Masukkan kode berikut untuk melanjutkan proses verifikasi:
                            </p>
                        </td>
                    </tr>

                    <!-- OTP BOX Center -->
                    <tr>
                        <td align="center" style="padding:25px 0;">
                            <table border="0" cellspacing="0" cellpadding="0" 
                                   style="background:#eef2ff; border-radius:8px; padding:15px 40px;">
                                <tr>
                                    <td align="center"
                                        style="font-size:34px; letter-spacing:10px; font-weight:bold; color:#3b5cff;">
                                        {{ $otp }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Info -->
                    <tr>
                        <td align="center" style="padding-top:10px;">
                            <p style="margin:0; font-size:14px; color:#777;">
                                Kode ini berlaku selama <strong>5 menit</strong>.
                            </p>
                            <p style="margin:10px 0 0; font-size:14px; color:#777;">
                                Jangan bagikan kepada siapa pun.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding-top:35px; font-size:12px; color:#aaa;">
                            © {{ date('Y') }} tamasoft.cloud. All rights reserved.
                        </td>
                    </tr>

                </table>
                <!-- End Card -->

            </td>
        </tr>
    </table>

</body>
</html>
