<x-guest-layout>
    <div class="text-center mgb-20">
        <h2 class="fw-700 font-hornsea-fc color-black fs-40 lh-11 mgb-40">{{ __('Resend OTP') }}</h2>
    </div>
    <div class="mb-4 text-success fs-15 text-center">
        {{ __('Thanks for signing up! If you didn’t receive your OTP or it got expired, don’t worry—we’ve got you covered. Simply enter your email address below, and we’ll resend the 6-digit OTP to help you verify your account. For any assistance, feel free to reach out to us at '.config('app.support_email')) }}
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 p-3" :status="session('status')" />
    <x-input-error :messages="$errors->get('message')" class="mb-4 p-3" />

    <form method="POST" action="{{ route('verification.otp.resend') }}">
        @csrf

        <!-- Email Address -->
        <div class="field-block">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-center mt-4">
            <x-primary-button>
                {{ __('Resend') }}
            </x-primary-button>
        </div>
        <div class="d-flex align-items-center justify-content-center mt-4">
            <a href="{{ route('login') }}" class="color-midnight text-decoration-underline fw-400 fs-15">
                {{ __('Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>
