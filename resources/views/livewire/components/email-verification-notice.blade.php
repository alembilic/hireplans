<div class="bg-red-500 text-white p-4 text-lg text-center">
    {{ __('Please verify your email address. Check your inbox for a verification email.') }}
    <br>
    {{ __('You cannot apply for jobs until you verify your email address.') }}
    <br>
    {{ __('You can request a new verification email from') }} <a href="{{ route('verification.notice') }}" class="underline">{{ __('here') }}</a>.
</div>

