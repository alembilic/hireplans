@component('mail::message')
# Reference Request

The candidate {{ $candidate_name }} has made a reference request for {{ $reference_name }}.

Please click the button below to complete the reference request and provide feedback about the candidate.

<x-mail::button :url="$url">
Complete Reference Request
</x-mail::button>

Thanks,<br>

{{ config('app.name') }}
{{ config('company.email') }}
@endcomponent
