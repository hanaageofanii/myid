<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reminder Upload Data Sore</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #fff4ed; margin: 0; padding: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0"
           style="max-width: 650px; margin: auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow: hidden;">

        <tr>
            <td style="text-align: center; padding: 25px; background: linear-gradient(135deg, #ff7e5f, #feb47b);">
                <img src="{{ $logo($message) }}" alt="Logo Perusahaan" style="max-height: 65px;">
            </td>
        </tr>

        <tr>
            <td style="padding: 45px;">
                <h2 style="color: #d16002; margin-bottom: 15px;">Selamat Sore, Rekan-rekan Hebat 🌇</h2>
                <p style="color: #666; font-size: 14px; margin-bottom: 25px; text-align: right;">
                    <strong>{{ $tanggal }}</strong>
                </p>
                <p style="font-size: 15px; color: #333; line-height: 1.6;">
                    Hari ini sudah luar biasa! Sebelum beristirahat, yuk pastikan semua
                    <strong>data harian sudah diupload</strong> agar tim lain bisa melanjutkan dengan lancar.
                </p>
                <p style="font-size: 15px; color: #333; line-height: 1.6;">
                    Data yang rapi hari ini adalah fondasi untuk keputusan yang lebih cepat dan tepat besok.
                    Terima kasih atas komitmen dan tanggung jawab Anda 🙏
                </p>

                <p style="margin-top: 40px; font-size: 14px; color: #666;">
                    Salam hangat,<br>
                    <strong>Tim Monitoring & Data</strong>
                </p>
            </td>
        </tr>

        <tr style="background-color: #ffe8d6;">
            <td style="padding: 15px; text-align: center; font-size: 12px; color: #777;">
                © {{ date('Y') }} PT. PURNAMA KARYA BERSAMA & PT. AGUNG PURNAMA BAKTI<br>
            </td>
        </tr>
    </table>
</body>
</html>
