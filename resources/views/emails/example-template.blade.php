{{-- EXAMPLE EMAIL TEMPLATE --}}
{{-- Copy this template to create new emails with consistent branding --}}

@extends('emails.layouts.base')

@section('title', 'Your Email Title')
@section('email-title', '✨ Your Email Subject')

@section('content')
    <div class="welcome-text">
        <p>Hello {{ $user->name ?? 'there' }},</p>
        
        <p>Your main email message goes here. This template provides consistent branding and styling.</p>
    </div>

    {{-- Action Button --}}
    <div class="action-buttons">
        <a href="#" class="primary-button">
            Primary Action
        </a>
        <a href="#" class="btn-secondary">
            Secondary Action
        </a>
    </div>

    {{-- Information Box --}}
    <div class="info-box">
        <strong>💡 Info:</strong> Use info-box for general information
    </div>

    {{-- Warning Box --}}
    <div class="warning-box">
        <strong>⚠️ Warning:</strong> Use warning-box for important notices
    </div>

    {{-- Success Box --}}
    <div class="success-box">
        <strong>✅ Success:</strong> Use success-box for positive messages
    </div>

    {{-- Two-column layout --}}
    <div class="info-grid">
        <div class="info-section">
            <h3>🔧 Column 1</h3>
            <div class="info-item">
                <strong>Label:</strong> Value
            </div>
        </div>
        
        <div class="info-section">
            <h3>📊 Column 2</h3>
            <div class="info-item">
                <strong>Label:</strong> Value
            </div>
        </div>
    </div>

    {{-- Summary Box --}}
    <div class="summary-box">
        <h2 style="margin-top: 0;">Summary Title</h2>
        <p>Use summary-box for important summaries or highlights.</p>
    </div>
@endsection

@section('footer-content')
    {{-- Optional: Custom footer content --}}
    <p>Custom footer message if needed.<br>
    <strong>{{ config('app.name') }}</strong></p>
@endsection

{{-- 
AVAILABLE CSS CLASSES:
- .welcome-text, .content-text - Main text content
- .primary-button, .btn - Primary action buttons
- .btn-secondary - Secondary buttons
- .action-buttons - Center-aligned button container
- .info-box - General information (blue accent)
- .warning-box - Warnings/notices (yellow accent)
- .success-box - Success messages (green accent)
- .summary-box - Important summaries (blue accent)
- .info-grid - Two-column responsive layout
- .info-section - Individual sections within grid
- .info-item - Individual data items
- .link-fallback - Styled links for fallback text
--}}
