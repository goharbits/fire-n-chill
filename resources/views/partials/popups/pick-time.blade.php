<div style="display:none;" class="page-popup-section pick-time popup" aria-modal="pick-time" id="pick-time">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center">
            <p class="color-midnight lh-15 fs-20 fw-300 mgb-15 selected-date">{{ __('October, Saturday 6th.') }}</p>
            <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('PICK A TIME SLOT') }}</h2>
        </div>
        <div class="popup-holder">
            @include('partials.time-list')
        </div>
        <div class="popup-footer">
            <div class="btn-wrap text-center time-picked">
                <button type="button" class="btn ssm bg-orange color-white text-center border-radius-50 lh-50 confirm-time-slot" disabled id="confirm-time-slot">
                    <span class="fs-16 color-midnight fw-600 text-uppercase">{{ __('PICK') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
