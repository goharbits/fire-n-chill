<section>
    <header>
        <h2 class="mb-4 color-midnight">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 color-midnight">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>
    <div class="container-sm mx-0 px-0">
        <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-6">
            @csrf
            @method('put')

            <div class="field-block">
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div class="field-block">
                <x-input-label for="update_password_password" :value="__('New Password')" />
                <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div class="field-block">
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="btn-wrap d-flex justify-content-center gap-4 flex-column">
                @if (session('status') === 'password-updated')
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
