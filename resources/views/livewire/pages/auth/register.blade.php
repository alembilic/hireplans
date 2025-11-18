<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Orchid\Platform\Models\Role;

new #[Layout('layouts.auth')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $agreed_to_terms = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'agreed_to_terms' => ['accepted'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        // Add default roles
        $role1 = Role::where('slug', 'authenticated_user')->first();
        $role2 = Role::where('slug', 'candidate')->first();
        $user->replaceRoles([$role1->id, $role2->id]);

        Auth::login($user);

        // $this->redirect(route('dashboard', absolute: false), navigate: true);
        $this->redirect(route('platform.profile', absolute: false));
    }
}; ?>

<div>
    <h1 class="auth-title">Create Your Account</h1>
    
    <form wire:submit="register" class="auth-form">
        <!-- Name -->
        <div class="form-group">
            <label for="name">{{ __('Full Name') }}</label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name" />
            @error('name')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email">{{ __('Email Address') }}</label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username" />
            @error('email')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password" />
            @error('password')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            @error('password_confirmation')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <!-- Terms and Privacy Agreement -->
        <div class="form-group">
            <label class="checkbox-container">
                <input wire:model="agreed_to_terms" id="agreed_to_terms" type="checkbox" name="agreed_to_terms" required />
                <span class="checkbox-label">
                    I agree to the <a href="{{ route('terms-of-use') }}" target="_blank">Terms of Use</a> and <a href="{{ route('privacy-policy') }}" target="_blank">Privacy Policy</a>
                </span>
            </label>
            @error('agreed_to_terms')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-button">
            {{ __('Create Account') }}
        </button>
        
        <div class="auth-links">
            <a href="{{ route('login') }}" class="primary-link">
                {{ __('Already have an account? Sign in') }}
            </a>
        </div>
    </form>
</div>
