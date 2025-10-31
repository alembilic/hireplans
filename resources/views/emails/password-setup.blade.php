<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your Password</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #a16207;
            margin-bottom: 10px;
        }
        h1 {
            color: #2d3748;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .welcome-text {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .setup-button {
            display: inline-block;
            background-color: #a16207;
            color: #ffffff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
            text-align: center;
        }
        .setup-button:hover {
            background-color: #92400e;
        }
        .security-note {
            background-color: #f3f4f6;
            border-left: 4px solid #a16207;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .link-fallback {
            word-break: break-all;
            color: #a16207;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1>Welcome! Set Up Your Password</h1>
        </div>

        <div class="welcome-text">
            <p>Hello {{ $user->name }},</p>
            
            <p>Your account has been created on {{ config('app.name') }}. To complete your registration and secure your account, please set up your password by clicking the button below:</p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $setupUrl }}" class="setup-button">
                Set Up My Password
            </a>
        </div>

        <div class="security-note">
            <strong>🔒 Security Note:</strong> This link is secure and will expire in 60 minutes for your protection. If you didn't expect this email, you can safely ignore it.
        </div>

        <p style="margin-top: 25px;">If the button above doesn't work, copy and paste this link into your browser:</p>
        <p><a href="{{ $setupUrl }}" class="link-fallback">{{ $setupUrl }}</a></p>

        <div class="footer">
            <p>This email was sent by {{ config('app.name') }}<br>
            If you have any questions, please contact your administrator.</p>
        </div>
    </div>
</body>
</html>
