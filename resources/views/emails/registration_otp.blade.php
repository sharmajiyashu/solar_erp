<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #71bbb2, #27445D);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .content {
            padding: 40px;
            text-align: center;
            color: #333333;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #71bbb2;
            letter-spacing: 5px;
            margin: 30px 0;
            padding: 15px;
            background-color: #f0fdf4;
            border: 2px dashed #71bbb2;
            display: inline-block;
            border-radius: 10px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }
        .btn {
            background-color: #71bbb2;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Solar Arkshakti Solutions</h1>
            <p>Empowering the Future with Clean Energy</p>
        </div>
        <div class="content">
            <h2>Verify Your Registration</h2>
            <p>Thank you for choosing Solar Arkshakti Solutions. To complete your registration and activate your account, please use the following One-Time Password (OTP):</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This OTP is valid for the next 10 minutes. Please do not share this code with anyone.</p>
            <p>If you did not request this registration, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Solar Arkshakti Solutions. All rights reserved.</p>
            <p>Pushp Enclave Pratap nagar Main Tonk Road Jaipur, Jaipur, RJ 302033</p>
        </div>
    </div>
</body>
</html>
