<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
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
            @yield('header-style', 'padding-bottom: 20px; border-bottom: 2px solid #a16207;')
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
        h2 {
            color: #a16207;
            font-size: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        h3 {
            color: #a16207;
            font-size: 16px;
            margin-bottom: 10px;
            margin-top: 20px;
        }
        .welcome-text, .content-text {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .btn, .primary-button {
            display: inline-block;
            background-color: #a16207;
            color: #ffffff;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 10px;
            text-align: center;
        }
        .btn:hover, .primary-button:hover {
            background-color: #92400e;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: #ffffff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 10px;
            display: inline-block;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        .info-box, .security-note, .notification-box {
            background-color: #f3f4f6;
            border-left: 4px solid #a16207;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .warning-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .success-box {
            background-color: #f0fdf4;
            border-left: 4px solid #22c55e;
            padding: 15px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 25px 0;
        }
        .info-section {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        .info-item {
            margin-bottom: 8px;
        }
        .info-item strong {
            color: #374151;
            display: inline-block;
            min-width: 100px;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
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
        .summary-box {
            background-color: #f3f4f6;
            border-left: 4px solid #a16207;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        /* Responsive Design */
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .email-container {
                padding: 20px;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
            .btn, .primary-button, .btn-secondary {
                display: block;
                margin: 10px 0;
                text-align: center;
            }
        }
        @yield('custom-styles')
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1>@yield('email-title')</h1>
        </div>

        @yield('content')

        <div class="footer">
            @hasSection('footer-content')
                @yield('footer-content')
            @else
                @include('emails.partials.footer')
            @endif
        </div>
    </div>
</body>
</html>
