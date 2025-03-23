<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Akun Anda</title>
</head>
<body>
    <h2>Halo!</h2>
    <p>Terima kasih telah mendaftar. Klik tombol di bawah ini untuk memverifikasi akun Anda:</p>
    <p>
        <a href="{{ url('/api/verify-email?token=' . $token) }}" 
           style="display:inline-block; padding:10px 20px; background-color:#28a745; color:#fff; text-decoration:none; border-radius:5px;">
           Verifikasi Email
        </a>
    </p>
    <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
</body>
</html>
