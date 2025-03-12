<x-guest-layout>
    <div class="text-center mgb-20">
        <h2 class="fw-700 font-hornsea-fc color-black fs-40 lh-11 mgb-40">{{ __('Forgot password') }}</h2>
    </div>
    <div class="mb-4 text-success fs-15 text-center">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you an OTP that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="field-block">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center justify-content-center mt-4">
            <x-primary-button>
                {{ __('Email OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
