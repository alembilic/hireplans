@extends('emails.layouts.base')

@section('title', 'Set Up Your Password')
@section('email-title', 'Welcome! Set Up Your Password')

@section('content')
    <div class="welcome-text">
        <p>Hello {{ $user->name }},</p>
        
        <p>Your account has been created on {{ config('app.name') }}. To complete your registration and secure your account, please set up your password by clicking the button below:</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $setupUrl }}" class="primary-button">
            Set Up My Password
        </a>
    </div>

    <div class="security-note">
        <strong>🔒 Security Note:</strong> This link is secure and will expire in 60 minutes for your protection. If you didn't expect this email, you can safely ignore it.
    </div>

    <p style="margin-top: 25px;">If the button above doesn't work, copy and paste this link into your browser:</p>
    <p><a href="{{ $setupUrl }}" class="link-fallback">{{ $setupUrl }}</a></p>
@endsection
