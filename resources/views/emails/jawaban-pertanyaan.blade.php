<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Jawaban Pertanyaan</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">

    <div style="max-width:600px; margin:auto; background:white; padding:24px; border-radius:8px;">

        <h2 style="color:#27C2DE; margin-bottom:20px;">
            Jawaban dari Admin PPDB MAN Jeneponto
        </h2>

        <p style="margin-bottom:16px;">
            Halo <strong>{{ $pertanyaan->pengirim }}</strong>,
        </p>

        <p style="margin-bottom:16px;">
            Terima kasih telah menghubungi kami. Berikut adalah jawaban dari pertanyaan Anda:
        </p>

        <hr style="margin:20px 0;">

        <p><strong>Pertanyaan:</strong></p>
        <p style="background:#F1F5F9; padding:12px; border-radius:6px;">
            {{ $pertanyaan->pertanyaan }}
        </p>

        <p style="margin-top:20px;"><strong>Jawaban:</strong></p>
        <p style="background:#ECFEFF; padding:12px; border-radius:6px;">
            {{ $pertanyaan->jawaban }}
        </p>

        <hr style="margin:20px 0;">

        <p style="font-size:13px; color:#64748B;">
            Jika masih ada pertanyaan lain, silakan hubungi kami kembali.
        </p>

        <p style="margin-top:20px;">
            Salam,<br>
            <strong>Admin PPDB MAN Jeneponto</strong>
        </p>

    </div>

</body>
</html>