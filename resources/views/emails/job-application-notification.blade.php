@extends('emails.layouts.base')

@section('title', 'New Job Application')
@section('email-title', '🎯 New Job Application Received')

@section('content')
    <div class="summary-box">
        <h2 style="margin-top: 0;">Application Summary</h2>
        <div class="info-item">
            <strong>Application ID:</strong> {{ $jobApplication->application_ref }}
        </div>
        <div class="info-item">
            <strong>Applied Date:</strong> {{ $jobApplication->created_at->format('M d, Y \a\t g:i A') }}
        </div>
        <div class="info-item">
            <strong>Job Position:</strong> {{ $job->title }}
        </div>
        <div class="info-item">
            <strong>Company:</strong> {{ $employer->name }}
        </div>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h3>👤 Candidate Information</h3>
            <div class="info-item">
                <strong>Name:</strong> {{ $user->name }}
            </div>
            <div class="info-item">
                <strong>Email:</strong> {{ $user->email }}
            </div>
            <div class="info-item">
                <strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}
            </div>
            <div class="info-item">
                <strong>Location:</strong> {{ $user->city ? $user->city . ($user->country ? ', ' . $user->country : '') : 'Not provided' }}
            </div>
            @if($candidate->candidate_ref)
            <div class="info-item">
                <strong>Candidate ID:</strong> {{ $candidate->candidate_ref }}
            </div>
            @endif
        </div>

        <div class="info-section">
            <h3>💼 Job Details</h3>
            <div class="info-item">
                <strong>Position:</strong> {{ $job->title }}
            </div>
            <div class="info-item">
                <strong>Company:</strong> {{ $employer->name }}
            </div>
            <div class="info-item">
                <strong>Location:</strong> {{ $job->location ?? 'Not specified' }}
            </div>
            <div class="info-item">
                <strong>Job Type:</strong> {{ $job->job_type ?? 'Not specified' }}
            </div>
            <div class="info-item">
                <strong>Salary:</strong> {{ $job->salary ? '$' . number_format($job->salary) : 'Not disclosed' }}
            </div>
        </div>
    </div>

    @if($jobApplication->cv)
    <div class="warning-box">
        <strong>📄 CV Attached:</strong> The candidate has submitted their CV with this application. 
        <em>Access the full application details through the admin panel to download and review the CV.</em>
    </div>
    @endif

    <div class="action-buttons">
        <a href="{{ config('app.url') }}/admin/job_applications/{{ $jobApplication->id }}" class="btn">
            View Full Application
        </a>
        <a href="{{ config('app.url') }}/admin/candidates/{{ $candidate->id }}" class="btn-secondary">
            View Candidate Profile
        </a>
    </div>

    @if($candidate->skills || $candidate->languages)
    <div class="info-section" style="margin-top: 25px;">
        <h3>🎯 Candidate Skills & Languages</h3>
        @if($candidate->skills)
        <div class="info-item">
            <strong>Skills:</strong> {{ $candidate->skills }}
        </div>
        @endif
        @if($candidate->languages)
        <div class="info-item">
            <strong>Languages:</strong> {{ $candidate->languages }}
        </div>
        @endif
        @if($candidate->work_experiences)
        <div class="info-item" style="margin-top: 10px;">
            <strong>Experience:</strong> {{ Str::limit($candidate->work_experiences, 200) }}
        </div>
        @endif
    </div>
    @endif
@endsection

@section('footer-content')
    <p>This notification was sent automatically when a new job application was submitted.<br>
    <strong>{{ config('app.name') }}</strong> - Talent Management System</p>
    <p style="margin-top: 15px; font-size: 12px;">
        You can manage all applications and candidates through the admin dashboard.
    </p>
@endsection
