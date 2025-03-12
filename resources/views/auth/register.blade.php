<x-guest-layout>
    <div class="text-center mgb-20">
        <h2 class="fw-700 font-hornsea-fc color-black fs-40 lh-11 mgb-40">{{ __('Create a new account') }}</h2>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 p-3" :status="session('status')" />

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- First name -->
        <div class="field-block">
            <x-input-label for="first_name" :value="__('First name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')"
                required autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last name -->
        <div class="field-block">
            <x-input-label for="last_name" :value="__('Last name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')"
                required autofocus autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="field-block">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Date of birth -->
        {{-- <div class="field-block">
            <x-input-label for="birth_date" :value="__('Date of birth')" />
            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" :value="old('birth_date')" required autofocus autocomplete="birth_date" />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div> --}}

        <!-- Password -->
        <div class="field-block">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="field-block">
            <x-input-label for="confirm_password" :value="__('Confirm Password')" />

            <x-text-input id="confirm_password" class="block mt-1 w-full" type="password" name="confirm_password"
                required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('confirm_password')" class="mt-2" />
        </div>

        <x-input-error :messages="$errors->get('message')" class="mb-4 p-3" />

        <div class="text-webkit-center mb-4">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('captcha')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="d-flex align-items-center justify-content-center mgt-20">
            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="d-flex align-items-center justify-content-center mgt-20">
            <a class="color-midnight text-decoration-underline fw-400 fs-15" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>
    </form>
    @section('scripts')
        @parent
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endsection

</x-guest-layout>
