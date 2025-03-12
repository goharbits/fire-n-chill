<div style="display:none;" class="page-popup-section popup" aria-modal="verify-otp" id="verify-otp">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center">
            <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-10">{{ __('Verify OTP') }}</h2>
            <p class="fs-15 font-altivo text-center fw-300 mgb-30 lh-16">{{ __('Thanks for signing up! Before getting started, please verify your email address by entering the 6 digit OTP we just emailed to you. Feel free to reachout to'.config('app.support_email').' for any assistance.') }}</p>
        </div>
        <div class="popup-verify popup-holder">
            <div class="poup-form verify-otp-form">
                <form method="POST" action="{{ route('post.email.verification') }}" data-js-form>
                    @csrf
                    <input type="hidden" name="form_type" value="otp">
                    <div class="field-block">
                        <input type="text" name="otp" required pattern="\d{6}" maxlength="6">
                    </div>
                    <div class="field-block">
                        <p class="form-message"></p>
                    </div>
                    <div class="form-btn text-center">
                       <button type="submit" class="btn bg-orange color-white text-center align-items-center justify-content-center d-flex border-radius-50 submit-btn"><span>{{ __('Verify') }}</span></button>
                    </div>
                    <div class="form-link text-center">
                        <a class="color-midnight text-decoration-underline fw-600 fs-20 open-popup" data-target="resend-otp" href="javascript:void(0)">{{ __('Resend OTP') }}</a>
                    </div> 
                </form>
            </div>
        </div>
    </div>
</div>

@include('partials.popups.resend-otp')