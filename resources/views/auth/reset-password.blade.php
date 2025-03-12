<x-guest-layout>
    <div class="text-center mgb-20">
        <h2 class="fw-700 font-hornsea-fc color-black fs-40 lh-11 mgb-40">{{ __('Reset password') }}</h2>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 p-3" :status="session('status')" />
    <x-input-error :messages="$errors->get('message')" class="mb-4 p-3" />

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        {{-- <input type="hidden" name="token" value="{{ $request->route('token') }}"> --}}

        {{-- OTP --}}
        <div class="field-block">
            <x-input-label for="otp" :value="__('OTP')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" :value="old('otp', $request->otp)" required autofocus autocomplete="otp" pattern="\d{6}" maxlength="6" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="field-block">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="field-block">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="field-block">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="field-block">
            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-center mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
        <div class="d-flex align-items-center justify-content-center mt-4">
            <a href="{{ route('password.request') }}" class="color-midnight text-decoration-underline fw-400 fs-15">
                {{ __('Resend Password') }}
            </a>
        </div>
    </form>
</x-guest-layout>
