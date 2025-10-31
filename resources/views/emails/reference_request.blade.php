@extends('emails.layouts.base')

@section('title', 'Reference Request')
@section('email-title', '📝 Reference Request')

@section('content')
    <div class="content-text">
        <p>Hello,</p>
        
        <p>The candidate <strong>{{ $candidate_name }}</strong> has requested a reference from <strong>{{ $reference_name }}</strong>.</p>
        
        <p>Please click the button below to complete the reference request and provide feedback about the candidate.</p>
    </div>

    <div class="action-buttons">
        <a href="{{ $url }}" class="primary-button">
            Complete Reference Request
        </a>
    </div>

    <div class="info-box">
        <strong>📋 What to expect:</strong> You'll be asked to provide professional feedback about the candidate's skills, work ethic, and suitability for employment. This will help employers make informed hiring decisions.
    </div>
@endsection

@section('footer-content')
    <p>Thanks,<br>
    <strong>{{ config('app.name') }}</strong><br>
    {{ config('company.email', '') }}</p>
@endsection
