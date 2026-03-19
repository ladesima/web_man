<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Email</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 10px rgba(0,0,0,0.05);">

                    {{-- HEADER --}}
                    <tr>
                        <td align="center" style="background:#16a34a; padding:20px;">
                            <img src="https://play-lh.googleusercontent.com/MLTM-FraiNEgsYIy7QBt8S_570jagM3M3pZUk89eaheGjJbLKoZc5QDUxJfOxD-RRLo=w600-h300-pc0xffffff-pd" width="60">
                            <h2 style="color:white; margin:10px 0 0;">PPDB MAN Jeneponto</h2>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding:30px; text-align:center;">

                            <h3 style="margin-bottom:10px;">Halo 👋</h3>

                            <p style="color:#555; font-size:14px; line-height:1.6;">
                                Terima kasih telah mendaftar di <b>PPDB MAN Jeneponto</b>.<br>
                                Silakan klik tombol di bawah untuk verifikasi email kamu.
                            </p>

                            {{-- BUTTON --}}
                            <a href="{{ $actionUrl }}"
                               style="display:inline-block; margin-top:20px; background:#16a34a; color:white;
                                      padding:12px 25px; border-radius:8px; text-decoration:none;
                                      font-weight:bold;">
                                Verifikasi Email
                            </a>

                            <p style="margin-top:25px; font-size:12px; color:#777;">
                                Jika kamu tidak merasa mendaftar, abaikan email ini.
                            </p>

                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background:#f9fafb; text-align:center; padding:15px; font-size:12px; color:#999;">
                            © {{ date('Y') }} PPDB MAN Jeneponto
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>