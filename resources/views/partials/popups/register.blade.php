<div style="display:none;" class="page-popup-section popup" aria-modal="create-account" id="create-account">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-logo text-center mgb-40">
            <img src="{{ Vite::image('fc-icon-popup.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center mgb-30">
            <h2 class="fw-700 font-hornsea-fc color-black fs-80 lh-11">{{ __('Create new account') }}</h2>
        </div>
        <div class="popup-login popup-holder">
            <div class="poup-form createNewAccount">
                <form method="POST" action="{{ route('register') }}" data-js-form>
                    @csrf
                    <input type="hidden" name="form_type" value="register">
                    <div class="field-block">
                        <input type="text" name="first_name" :value="old('first_name')" required autofocus
                            autocomplete="first_name" placeholder="{{ __('First Name') }}">
                    </div>
                    <div class="field-block">
                        <input type="text" name="last_name" :value="old('last_name')" required autofocus
                            autocomplete="last_name" placeholder="{{ __('Last Name') }}">
                    </div>
                    <div class="field-block">
                        <input type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="username" placeholder="{{ __('Email') }}">
                    </div>
                    {{-- <div class="field-block">
                        <input type="text" name="birth_date" onfocus="(this.type='date')" onblur="(this.type='text')" :value="old('birth_date')" required autofocus autocomplete="birth_date" placeholder="{{ __('Date of birth') }}">
                    </div> --}}
                    <div class="field-block">
                        <input type="password" name="password" required autocomplete="new-password"
                            placeholder="{{ __('Password') }}">
                    </div>
                    <div class="field-block">
                        <input type="password" name="confirm_password" required autocomplete="new-password"
                            placeholder="{{ __('Confirm password') }}">
                    </div>

                    <div class="text-webkit-center mt-4">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                    </div>

                    <div class="field-block">
                        <p class="form-message"></p>
                    </div>

                    <div class="form-btn text-center">
                        <button type="submit"
                            class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn"><span>{{ __('Sign up') }}</span></button>
                    </div>
                    <div class="form-link text-center">
                        <a class="color-midnight text-decoration-underline fw-600 fs-20 open-popup" data-target="login"
                            href="javascript:void(0)">{{ __('Already have an account') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('scripts')
    @parent
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
