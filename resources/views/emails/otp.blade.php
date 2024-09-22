<!DOCTYPE html>
<html>

<head>
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #4CAF50;
            font-size: 26px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.8;
            color: #555;
        }

        .otp-box {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            background-color: #f4f4f4;
            padding: 12px 30px;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
            border: 1px solid #4CAF50;
        }

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            display: inline-block;
            margin-top: 20px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

        .divider {
            border-top: 1px solid #e0e0e0;
            margin: 30px 0;
        }

        .small-text {
            font-size: 14px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Password Reset Request</h1>
        <p>Hello,</p>
        <p>We received a request to reset your password. Please use the following OTP to proceed:</p>
        <p class="otp-box">{{ $otp }}</p>
        <p>This OTP is valid for 15 minutes.</p>
        <p>If you did not request a password reset, please ignore this email or contact support for assistance.</p>
        <div class="divider"></div>
            <p>© 2024 Your Company. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
