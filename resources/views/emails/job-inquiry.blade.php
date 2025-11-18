@extends('emails.layouts.base')

@section('content')
    <h2 style="margin-top: 0; color: #0A0A0A; font-size: 24px; font-weight: 700; margin-bottom: 16px;">
        New Job Posting Inquiry
    </h2>

    <p style="margin-bottom: 24px; color: #4B4B4B; line-height: 1.6;">
        You have received a new job posting inquiry from a company looking to hire talent.
    </p>

    <div class="info-box">
        <h3 style="margin-top: 0; color: #0A0A0A; font-size: 18px; font-weight: 600; margin-bottom: 12px;">
            Company Information
        </h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 8px 0; color: #6B7280; font-weight: 600; width: 140px;">Company:</td>
                <td style="padding: 8px 0; color: #0A0A0A;">{{ $inquiryData['company'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6B7280; font-weight: 600;">Contact Email:</td>
                <td style="padding: 8px 0; color: #0A0A0A;">
                    <a href="mailto:{{ $inquiryData['email'] }}" style="color: #D4A017; text-decoration: underline;">
                        {{ $inquiryData['email'] }}
                    </a>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6B7280; font-weight: 600;">Position:</td>
                <td style="padding: 8px 0; color: #0A0A0A;">{{ $inquiryData['position'] }}</td>
            </tr>
            <tr>
                <td style="padding: 8px 0; color: #6B7280; font-weight: 600;">Location:</td>
                <td style="padding: 8px 0; color: #0A0A0A;">{{ $inquiryData['location'] }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <h3 style="margin-top: 0; color: #0A0A0A; font-size: 18px; font-weight: 600; margin-bottom: 12px;">
            Position Details
        </h3>
        <div style="background-color: #FAFAFA; border-left: 4px solid #F4C542; padding: 16px; border-radius: 4px;">
            <p style="margin: 0; color: #4B4B4B; line-height: 1.6; white-space: pre-wrap;">{{ $inquiryData['details'] }}</p>
        </div>
    </div>

    <div class="action-buttons">
        <a href="mailto:{{ $inquiryData['email'] }}" class="button-primary">
            Reply to Company
        </a>
    </div>

    <div class="warning-box">
        <strong>Action Required:</strong> Please respond to this inquiry as soon as possible to provide excellent customer service.
    </div>
@endsection
