<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        // $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        // Redirect user to Orchid dashboard
        // $this->redirectIntended(route('platform.index'), true);
        $this->redirectIntended(default: route('platform.index', absolute: false));
    }
}; ?>

<div>
    <h1 class="auth-title">Welcome Back</h1>
    
    <!-- Session Status -->
    @if (session('status'))
        <div class="success-text">{{ session('status') }}</div>
    @endif

    <form wire:submit="login" class="auth-form">
        <!-- Email Address -->
        <div class="form-group">
            <label for="email">{{ __('Email Address') }}</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username" />
            @error('form.email')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password" />
            @error('form.password')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-group">
            <div class="checkbox-group">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember">
                <label for="remember" class="checkbox-label">{{ __('Remember me') }}</label>
            </div>
        </div>

        <button type="submit" class="auth-button">
            {{ __('Sign In') }}
        </button>
        
        <div class="auth-links">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <a href="{{ route('register') }}" class="primary-link">
                {{ __('Don\'t have an account? Sign up') }}
            </a>
        </div>
    </form>
</div>
