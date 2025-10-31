@extends('emails.layouts.base')

@section('title', 'Email Campaign')
@section('email-title', $email_title ?? 'Message from ' . config('app.name'))

@section('content')
    <div class="content-text">
        {!! $email_content !!}
    </div>
@endsection

@section('footer-content')
    <p>Thanks,<br>
    <strong>{{ $creator_name }}</strong><br>
    {{ config('app.name') }}</p>
@endsection

