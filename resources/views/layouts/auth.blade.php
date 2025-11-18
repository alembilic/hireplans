<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'HirePlans') : config('app.name', 'HirePlans') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    
    <style>
        /* Auth-specific styles */
        .auth-layout {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            background-color: #FFFBF0;
            background-image: radial-gradient(circle, rgba(244, 197, 66, 0.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        
        .auth-card {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            background-color: #FFFFFF;
            border: 1px solid #EAEAEA;
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .auth-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo img {
            height: 50px;
            width: auto;
        }
        
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0A0A0A;
            text-align: center;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        
        .auth-form .form-group {
            margin-bottom: 1rem;
        }
        
        .auth-form label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #4B4B4B;
            margin-bottom: 0.5rem;
        }
        
        .auth-form input[type="text"],
        .auth-form input[type="email"],
        .auth-form input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #EAEAEA;
            border-radius: 0.375rem;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            background-color: #FFFFFF;
            color: #0A0A0A;
        }
        
        .auth-form input:focus {
            outline: 2px solid #F4C542;
            outline-offset: 0;
            border-color: #F4C542;
        }
        
        .auth-form .checkbox-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .auth-form .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
        }
        
        .auth-form input[type="checkbox"] {
            width: 18px;
            height: 18px;
            background-color: #FFFFFF;
            border: 2px solid #EAEAEA;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }
        
        .auth-form input[type="checkbox"]:checked {
            background-color: #F4C542;
            border-color: #D4A017;
        }
        
        .auth-form input[type="checkbox"]:checked::before {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #0A0A0A;
            font-weight: bold;
            font-size: 12px;
        }
        
        .auth-form input[type="checkbox"]:focus {
            outline: 2px solid #F4C542;
            outline-offset: 2px;
        }
        
        .auth-form input[type="checkbox"]:hover {
            border-color: #D4A017;
            background-color: #FEFEFE;
        }
        
        .auth-form .checkbox-label {
            font-size: 0.875rem;
            color: #4B4B4B;
            margin: 0;
            line-height: 1.5;
            font-weight: 400;
        }
        
        .auth-form .checkbox-label a {
            color: #D4A017;
            text-decoration: underline;
            font-weight: 500;
        }
        
        .auth-form .checkbox-label a:hover {
            color: #0A0A0A;
        }
        
        .auth-button {
            width: 100%;
            padding: 0.875rem 1.5rem;
            background-color: #F4C542;
            color: #0A0A0A;
            border: none;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 1rem;
        }
        
        .auth-button:hover {
            background-color: #D4A017;
        }
        
        .auth-button:active {
            transform: translateY(1px);
        }
        
        .auth-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1.5rem;
            text-align: center;
        }
        
        .auth-links a {
            color: #4B4B4B;
            font-size: 0.875rem;
            text-decoration: none;
            font-weight: 500;
        }
        
        .auth-links a:hover {
            color: #0A0A0A;
            text-decoration: underline;
        }
        
        .auth-links .primary-link {
            color: #D4A017;
            font-weight: 600;
        }
        
        .auth-links .primary-link:hover {
            color: #0A0A0A;
        }
        
        .error-text {
            color: #DC2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .success-text {
            color: #059669;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="auth-layout">
    <div class="auth-card">
        <!-- Logo -->
        <div class="auth-logo">
            <a href="/">
                <img src="/images/HirePlansLogo.png" alt="HirePlans" />
            </a>
        </div>
        
        {{ $slot }}
    </div>
    
    @livewireScripts
</body>
</html>
