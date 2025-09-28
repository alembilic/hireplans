@component('mail::message')

{!! $email_content !!}

Thanks,<br>
{{ $creator_name }}<br>
{{ config('app.name') }}

@endcomponent

