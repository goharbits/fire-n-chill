<div style="display:none;" class="page-popup-section popup" aria-modal="book-a-session" id="book-a-sessions">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-logo text-center mgb-40">
            <img src="{{ Vite::image('fc-icon-popup.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center mgb-30">
            <h2 class="fw-700 font-hornsea-fc color-black fs-80 lh-11">{{ __('LET’S GET STARTED') }}</h2>
        </div>
        <div class="popup-login popup-holder">
            <div class="btn-wrap">
                <a class="btn btn-has-account border-black color-midnight bg-orange-hover text-center border-radius-50 lh-50 open-popup"
                    data-target="login" href="javascript:void(0)">{{ __('Already have an account') }}</a>
                <a class="btn border-black color-midnight bg-orange-hover text-center border-radius-50 lh-50 open-popup"
                    data-target="create-account" href="javascript:void(0)">{{ __('Create a new account') }}</a>
                <a class="btn border-black color-midnight bg-orange-hover text-center border-radius-50 lh-50"
                    href="{{ route('guest.slots.available', ['guest' => 'true']) }}">{{ __('Continue as a Guest') }}</a>
            </div>
        </div>
    </div>
</div>
