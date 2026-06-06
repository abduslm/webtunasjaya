<!-- resources/views/emails/activated.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Aktivasi Akun - PT Tunas Jaya</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8FAFC;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            max-width: 450px;
            width: 90%;
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            text-align: center;
            border: 1px solid #E2E8F0;
        }
        .icon-box {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 24px auto;
        }
        .success-bg { background-color: #DCFCE7; color: #166534; }
        .error-bg { background-color: #FEE2E2; color: #991B1B; }
        
        h2 {
            color: #1E293B;
            margin-bottom: 12px;
            font-size: 22px;
            font-weight: 700;
        }
        p {
            color: #64748B;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .footer-text {
            font-size: 13px;
            color: #94A3B8;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    @if($success)
        <div class="icon-box success-bg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 36px; height: 36px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>
        <h2>Aktivasi Akun Berhasil!</h2>
        <p>{{ $message }}</p>
    @else
        <div class="icon-box error-bg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 36px; height: 36px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <h2>Aktivasi Akun Gagal</h2>
        <p>{{ $message }}</p>
    @endif

    <div class="footer-text">
        PT Tunas Jaya Bersinar Cemerlang &copy; {{ date('Y') }}
    </div>
</div>

</body>
</html>