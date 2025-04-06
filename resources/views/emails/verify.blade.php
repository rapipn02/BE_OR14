<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun UKM Neo Telemetri</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
            color: #333;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e1e5e9;
        }

        .email-header {
            background-color: #2E1461;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .logo {
            max-width: 150px;
            height: auto;
        }

        .email-body {
            padding: 30px;
            text-align: left;
        }

        .email-footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .btn {
            display: inline-block;
            background-color: #2E1461;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        h2 {
            color: #2E1461;
            margin-top: 0;
        }

        p {
            margin-bottom: 15px;
        }

        .alternative-link {
            display: block;
            margin-top: 15px;
            word-break: break-all;
            font-size: 13px;
            color: #0066cc;
        }

        .contact-info {
            margin-top: 20px;
            font-size: 14px;
        }

        .social-links {
            margin-top: 15px;
        }

        .social-links a {
            color: #2E1461;
            text-decoration: none;
            margin: 0 5px;
        }

        .unsubscribe {
            margin-top: 10px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://imgur.com/U7JdiXu.png" alt="Logo UKM Neo Telemetri" class="logo">
        </div>
        <div class="email-body">
            <h2>Verifikasi Email - Open Recruitment 14 UKM Neo Telemetri</h2>
            <p>Halo,</p>
            <p>Terima kasih telah mendaftar pada Open Recruitment 14 UKM Neo Telemetri. Untuk melanjutkan proses
                pendaftaran, mohon verifikasi alamat email Anda dengan klik tombol di bawah ini:</p>

            <div style="text-align: center;">
                <a href="{{ url('/api/verify-email?token=' . $token) }}" class="btn">
                    Verifikasi Email
                </a>
            </div>

            <p>Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan tautan berikut di browser Anda:
            </p>
            <div class="alternative-link">
                {{ url('/api/verify-email?token=' . $token) }}
            </div>

            <p>Email verifikasi ini akan kedaluwarsa dalam 24 jam.</p>

            <p>Jika Anda tidak pernah mendaftar atau tidak mengenali aktivitas ini, Anda dapat mengabaikan email ini.
            </p>

            <div class="contact-info">
                <p>Jika Anda memiliki pertanyaan, silakan hubungi kami di:</p>
                <p>Email: <a href="mailto:or.neotelemetri@gmail.com">or.neotelemetri@gmail.com</a><br>
                    WhatsApp: 089515908397 (Berka)</p>
            </div>

            <div class="social-links">
                <p>Ikuti kami di:
                    <a href="https://instagram.com/neotelemetri">Instagram</a> |
                </p>
            </div>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} UKM Neo Telemetri. Semua hak dilindungi.</p>
            <p class="unsubscribe">Jika Anda tidak ingin menerima email dari kami di masa mendatang, silakan <a
                    href="{{ url('/unsubscribe?email=' . urlencode($email)) }}">berhenti berlangganan</a>.</p>
        </div>
    </div>
</body>

</html>
