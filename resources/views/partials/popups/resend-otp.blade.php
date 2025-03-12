<div style="display:none;" class="page-popup-section popup" aria-modal="resend-otp" id="resend-otp">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center">
            <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('Resend OTP') }}</h2>
            <p class="fs-15 font-altivo text-center fw-300 mgb-30 lh-16">{{ __('Thanks for signing up! If you didn’t receive your OTP or it got expired, don’t worry—we’ve got you covered. Simply enter your email address below, and we’ll resend the 6-digit OTP to help you verify your account. For any assistance, feel free to reach out to us at '.config('app.support_email')) }}</p>
        </div>
        <div class="popup-verify popup-holder">
            <div class="poup-form verify-otp-form">
                <form method="POST" action="{{ route('verification.otp.resend') }}" data-js-form>
                    @csrf
                    <input type="hidden" name="form_type" value="resend-otp">
                    <div class="field-block">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="field-block">
                        <p class="form-message"></p>
                    </div>
                    <div class="form-btn text-center">
                        <button type="submit" class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn"><span>{{ __('Resend') }}</span></button>
                    </div>
                    {{-- <div class="d-flex align-items-center justify-content-center mt-4">
                        <a href="{{ route('password.request') }}" class="color-midnight text-decoration-underline fw-400 fs-15">
                            {{ __('Resend OTP') }}
                        </a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</div>