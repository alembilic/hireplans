<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <h1 class="auth-title">Reset Your Password</h1>
    
    <div style="color: #4B4B4B; font-size: 0.9375rem; margin-bottom: 1.5rem; line-height: 1.5;">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="success-text">{{ session('status') }}</div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="auth-form">
        <!-- Email Address -->
        <div class="form-group">
            <label for="email">{{ __('Email Address') }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus />
            @error('email')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-button">
            {{ __('Send Reset Link') }}
        </button>
        
        <div class="auth-links">
            <a href="{{ route('login') }}" class="primary-link">
                {{ __('Back to Sign In') }}
            </a>
        </div>
    </form>
</div>
