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
            background: linear-gradient(135deg, #FF7E20, #27445D);
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
            color: #FF7E20;
            letter-spacing: 5px;
            margin: 30px 0;
            padding: 15px;
            background-color: #fffaf0;
            border: 2px dashed #FF7E20;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Solar Arkshakti Solutions</h1>
            <p>Secure Password Reset</p>
        </div>
        <div class="content">
            <h2>Reset Your Password</h2>
            <p>We received a request to reset your password. Use the following One-Time Password (OTP) to proceed with your password reset:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This OTP is valid for the next 10 minutes. Please do not share this code with anyone.</p>
            <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Solar Arkshakti Solutions. All rights reserved.</p>
            <p>Pushp Enclave Pratap nagar Main Tonk Road Jaipur, Jaipur, RJ 302033</p>
        </div>
    </div>
</body>
</html>
