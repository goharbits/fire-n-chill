<x-guest-layout>
    <div class="text-center mgb-20">
        <h2 class="fw-700 font-hornsea-fc color-black fs-40 lh-11 mgb-40">{{ __('Login') }}</h2>
    </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <x-input-error :messages="$errors->get('message')" class="mb-4" />

    <form method="POST" action="{{ route('post.login.web') }}">
        @csrf

        <!-- Email Address -->
        <div class="field-block">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="field-block">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="field-block">
            <label for="remember_me" class="d-flex align-items-center gap-2">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-success fs-15 text-center">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="btn-wrap d-flex justify-content-center">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="d-flex align-items-center justify-content-between mgt-20">
            @if (Route::has('password.request'))
                <a class="color-midnight text-decoration-underline fw-400 fs-15" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            <a href="{{ route('register') }}" class="color-midnight text-decoration-underline fw-400 fs-15">
                {{ __('Create a new account') }}
            </a>
        </div>
    </form>
</x-guest-layout>
