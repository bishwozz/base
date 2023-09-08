<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        p {
            margin-bottom: 15px;
        }
        strong {
            font-weight: bold;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your One-Time Password (OTP)</h1>

        <p>Dear Sir,</p>

        <p>You have requested a One-Time Password (OTP) to complete your authentication process.</p>

        <p>Your OTP code is: <strong>{{ $otp }}</strong></p>

        <p>Please enter this OTP on the verification page to proceed. This OTP is valid for a single use and will expire after a certain time.</p>

        <p>If you haven't initiated this request, please ignore this email .</p>

        <p>Thank you for using our service.</p>

        <p class="footer">Regards,<br>E-cabinet</p>
    </div>
</body>
</html>
