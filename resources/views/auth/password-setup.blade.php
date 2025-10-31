<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Welcome! Please set up your password to complete your account registration.') }}
    </div>

    <form method="POST" action="{{ route('password.setup.store') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $email)" readonly />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- User Info -->
        @if($user)
        <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('Account Information') }}</h3>
            <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                <p><strong>{{ __('Name:') }}</strong> {{ $user->name }}</p>
                <p><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
            </div>
        </div>
        @endif

        <div class="flex items-center justify-end mt-6">
            <x-primary-button>
                {{ __('Set Up Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
