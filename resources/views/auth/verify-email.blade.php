<x-guest-layout>
    <div class="text-center mgb-20">
        <h2 class="fw-700 font-hornsea-fc color-black fs-40 lh-11 mgb-40">{{ __('Verify OTP') }}</h2>
    </div>
    <div class="mb-4 text-success fs-15 text-center">
        {{ __('Thanks for signing up! Before getting started, please verify your email address by entering the 6 digit OTP we just emailed to you. Feel free to reachout to '.config('app.support_email').' for any assistance.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 p-3" :status="session('status')" />
        
    <div class="mt-4 block text-center">
        <form method="POST" action="{{ route('verification.otp.verify') }}">
            @csrf
            <input type="hidden" name="form_type" value="otp">
            <div class="field-block">
                <x-input-label for="otp" :value="__('OTP')" />
                <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" :value="old('otp')" required autofocus autocomplete="otp" pattern="\d{6}" maxlength="6" />
                <x-input-error :messages="$errors->get('otp')" class="mt-2" />
            </div>

            <x-input-error :messages="$errors->get('message')" class="mb-4 p-3" />

            <div class="d-flex align-items-center justify-content-center mt-4">
                <x-primary-button>
                    {{ __('Verify OTP') }}
                </x-primary-button>
            </div>
            <div class="d-flex align-items-center justify-content-center mt-4">
                <a href="{{ route('resend.otp') }}" class="color-midnight text-decoration-underline fw-400 fs-15">
                    {{ __('Resend OTP') }}
                </a>
            </div>
        </form>
        {{-- <div class="d-flex align-items-center justify-content-center mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <button type="submit" class="btn ssm color-midnight fw-600 fs-15 btn__secondary">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div> --}}
    </div>
</x-guest-layout>
