<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #1a202c;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            background-color: #1a202c;
            padding: 10px 20px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .copy-text {
            font-size: 14px;
            color: #1a202c;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Mgoods</h1>
    <p>Hi,</p>
    <p>Email manzilingizni tasdiqlash uchun bir martalik OTP kodi..</p>

    <div class="otp-code" id="otp-code">{{ $otpCode }}</div>

    <p>Hurmat bilan,<br>Mgoods</p>
</div>

</body>
</html>
