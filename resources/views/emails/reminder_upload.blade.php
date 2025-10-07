<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reminder Upload Data Pagi</title>
</head>
<body style="font-family: 'Segoe UI', Arial, sans-serif; background-color: #fff7f0; margin: 0; padding: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0"
           style="max-width: 650px; margin: auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); overflow: hidden;">

        <!-- Header dengan gradasi oranye -->
        <tr>
            <td style="text-align: center; padding: 25px; background: linear-gradient(135deg, #ff8c00, #ffb347);">
                <img src="{{ $logo($message) }}" alt="Logo Perusahaan" style="max-height: 65px;">
            </td>
        </tr>

        <!-- Isi Email -->
        <tr>
            <td style="padding: 45px;">
                <h2 style="color: #e67300; margin-bottom: 15px;">Selamat Pagi, Rekan-rekan Hebat ☀️</h2>

                <p style="color: #666; font-size: 14px; margin-bottom: 25px; text-align: right;">
                <strong>{{ $tanggal }}</strong>
                </p>
                <p style="font-size: 15px; color: #333; line-height: 1.6;">
                    Semoga hari ini penuh semangat dan energi positif 🌞.
                    Sebagai pengingat ramah, jangan lupa untuk melakukan
                    <strong>upload data harian</strong> ya.
                </p>
                <p style="font-size: 15px; color: #333; line-height: 1.6;">
                    Data yang lengkap dan terkini membantu kita menjaga kelancaran operasional
                    dan mendukung pengambilan keputusan yang lebih akurat.
                </p>
                <p style="font-size: 15px; color: #333; line-height: 1.6;">
                    Terima kasih atas kerja keras dan dedikasi Anda.
                    Mari mulai hari ini dengan semangat dan langkah terbaik 💼✨
                </p>

                <p style="margin-top: 40px; font-size: 14px; color: #666;">
                    Salam hangat,<br>
                    <strong>Tim Monitoring & Data</strong>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr style="background-color: #fff2e0;">
            <td style="padding: 15px; text-align: center; font-size: 12px; color: #777;">
                © {{ date('Y') }} PT. PURNAMA KARYA BERSAMA & PT. AGUNG PURNAMA BAKTI<br>
            </td>
        </tr>
    </table>
</body>
</html>
