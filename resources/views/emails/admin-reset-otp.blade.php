<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
</head>

<body style="font-family: Arial, sans-serif; background-color:#f4f6f9; padding:20px;">
    <div style="max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:8px; text-align:center;">
        <h2 style="color:#333;">Admin Password Reset</h2>
        <p style="color:#555; font-size:16px;">
            We received a request to reset your password.<br>
            Use the OTP below to continue:
        </p>
        <div style="font-size:28px; font-weight:bold; color:#2d89ef; margin:20px 0;">
            {{ $otp }}
        </div>
        <p style="color:#777; font-size:14px;">
            If you did not request this, please ignore this email.
        </p>
        <p style="font-size:13px; color:#999;">Samruddhi Silk House</p>
    </div>
</body>

</html>