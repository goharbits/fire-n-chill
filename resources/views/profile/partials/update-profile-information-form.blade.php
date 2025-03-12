<section>
    <header>
        <h2 class="mb-4 color-midnight">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 color-midnight">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <div class="container-sm mx-0 px-0">
        <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-6">
            @csrf
            @method('patch')
    
            <div class="field-block">
                <x-input-label for="first_name" :value="__('First name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>
    
            <div class="field-block">
                <x-input-label for="last_name" :value="__('Last name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>
    
            {{-- <div class="field-block">
                <x-input-label for="birth_date" :value="__('Date of birth')" />
                <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $user->birth_date)" required autofocus autocomplete="birth_date" />
                <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
            </div> --}}
    
            <div class="field-block">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input disabled id="email" type="readonly" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
    
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Your email address is unverified.') }}
    
                            <button form="send-verification" class="btn ssm color-midnight fw-600 fs-15 btn__secondary">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>
    
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 alert-success">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
    
            <div class="btn-wrap d-flex justify-content-center gap-4 flex-column">
                @if (session('status') === 'profile-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-success fs-15 text-center"
                    >{{ __('Saved.') }}</p>
                @endif
                <x-primary-button>{{ __('Save') }}</x-primary-button>
    
            </div>
        </form>
    </div>
</section>
