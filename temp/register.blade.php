<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <form wire:submit="register">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Additional Fields -->
        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Address Line 1 -->
        <div class="mt-4">
            <x-input-label for="address_line_1" :value="__('Address Line 1')" />
            <x-text-input wire:model="address_line_1" id="address_line_1" class="block mt-1 w-full" type="text" name="address_line_1" />
            <x-input-error :messages="$errors->get('address_line_1')" class="mt-2" />
        </div>

        <!-- City -->
        <div class="mt-4">
            <x-input-label for="city" :value="__('City')" />
            <x-text-input wire:model="city" id="city" class="block mt-1 w-full" type="text" name="city" />
            <x-input-error :messages="$errors->get('city')" class="mt-2" />
        </div>

        <!-- Postcode -->
        <div class="mt-4">
            <x-input-label for="postcode" :value="__('Postcode')" />
            <x-text-input wire:model="postcode" id="postcode" class="block mt-1 w-full" type="text" name="postcode" />
            <x-input-error :messages="$errors->get('postcode')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input wire:model="country" id="country" class="block mt-1 w-full" type="text" name="country" />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- Nationality -->
        <div class="mt-4">
            <x-input-label for="nationality" :value="__('Nationality')" />
            <x-text-input wire:model="nationality" id="nationality" class="block mt-1 w-full" type="text" name="nationality" />
            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
        </div>

        <!-- Date of Birth -->
        <div class="mt-4">
            <x-input-label for="dob" :value="__('Date of Birth')" />
            <x-text-input wire:model="dob" id="dob" class="block mt-1 w-full" type="date" name="dob" />
            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <x-text-input wire:model="gender" id="gender" class="block mt-1 w-full" type="text" name="gender" />
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Current Company -->
        <div class="mt-4">
            <x-input-label for="current_company" :value="__('Current Company')" />
            <x-text-input wire:model="current_company" id="current_company" class="block mt-1 w-full" type="text" name="current_company" />
            <x-input-error :messages="$errors->get('current_company')" class="mt-2" />
        </div>

        <!-- Current Job Title -->
        <div class="mt-4">
            <x-input-label for="current_job_title" :value="__('Current Job Title')" />
            <x-text-input wire:model="current_job_title" id="current_job_title" class="block mt-1 w-full" type="text" name="current_job_title" />
            <x-input-error :messages="$errors->get('current_job_title')" class="mt-2" />
        </div>

        <!-- Languages -->
        <div class="mt-4">
            <x-input-label for="languages" :value="__('Languages')" />
            <x-text-input wire:model="languages" id="languages" class="block mt-1 w-full" type="text" name="languages" />
            <x-input-error :messages="$errors->get('languages')" class="mt-2" />
        </div>

        <!-- Skills -->
        <div class="mt-4">
            <x-input-label for="skills" :value="__('Skills')" />
            <x-text-input wire:model="skills" id="skills" class="block mt-1 w-full" type="text" name="skills" />
            <x-input-error :messages="$errors->get('skills')" class="mt-2" />
        </div>

        <!-- Notes -->
        <div class="mt-4">
            <x-input-label for="notes" :value="__('Notes')" />
            <textarea wire:model="notes" id="notes" class="block mt-1 w-full" name="notes"></textarea>
            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
