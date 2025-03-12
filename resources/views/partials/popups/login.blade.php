<div style="display:none;" class="page-popup-section popup" aria-modal="login" id="login">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-logo text-center mgb-40">
            <img src="{{ Vite::image('fc-icon-popup.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center mgb-30">
            <h2 class="fw-700 font-hornsea-fc color-black fs-80 lh-11">{{ __('LOG IN TO ACCOUNT') }}</h2>
        </div>
        <div class="popup-login popup-holder">
            <div class="poup-form loginform">
                <form method="POST" action="{{ route('login') }}" data-js-form>
                    @csrf
                    <input type="hidden" name="form_type" value="login">
                    <div class="field-block">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="{{ __('Email') }}">
                    </div>
                    <div class="field-block">
                        <input type="password" name="password" required autocomplete="current-password" placeholder="{{ __('Password') }}">
                    </div>
                    <div class="field-block">
                        <p class="form-message"></p>
                    </div>
                    <div class="form-btn text-center">
                       <button type="submit" class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn"><span>{{ __('LOG IN') }}</span></button>
                    </div>
                </form>
            </div>
            <div class="d-flex justify-content-between">
                <div class="form-link">
                    <a class="color-midnight text-decoration-underline fw-600 fs-24" href="{{ route('register') }}">{{ __('Create a new account') }}</a>
                </div>
                @if (Route::has('password.request'))
                    <div class="form-link text-center">
                        <a class="color-midnight text-decoration-underline fw-600 fs-24" href="{{ route('password.request') }}">{{ __('Reset password') }}</a>
                    </div>   
                @endif
            </div>
        </div>
    </div>
</div>