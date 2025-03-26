<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun Anda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            text-align: center;
        }

        .email-footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #888;
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
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('images/or14.svg') }}" alt="Logo" class="logo">
        </div>
        <div class="email-body">
            <h2>Selamat Datang di NeoTelemetri!</h2>
            <p>Halo!</p>
            <p>Terima kasih telah mendaftar. Untuk melanjutkan, silakan verifikasi alamat email Anda dengan klik tombol
                di bawah ini:</p>

            <a href="{{ url('/api/verify-email?token=' . $token) }}" class="btn">
                Verifikasi Email
            </a>

            <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} NeoTelemetri. Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>
