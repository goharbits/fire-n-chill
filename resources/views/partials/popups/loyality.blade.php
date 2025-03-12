<div style="display:none;" class="page-popup-section loyality-poup popup" aria-modal="loyality" id="loyality">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-logo text-center mgb-30">
            <img src="{{ Vite::image('fc-logo-orange-blue.svg') }}" alt="{{ __('FC logo') }}">
        </div>
        <div class="popup-title text-center mgb-30">
            <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('PEAK VITALITY PLAN') }}</h2>
            <p class="color-midnight lh-15 fs-20 fw-300 mgb-15">{{ __('2 sessions per week needed for 12 weeks to') }}<br> {{ __('reach') }} <span class="fw-600">{{ __('Peak Vitality') }}</span>.</p>
            <p class="color-midnight lh-15 fs-20 fw-300">{{ __('Work towards Peak Vitality and Unlock Rewards') }}<br> {{ __('at each stage.') }}</p>
        </div>
        <div class="btn-wrap text-center">
            <a class="btn ssm border-2x-blue color-midnight text-center border-radius-50 lh-50" href="{{ route('client.slots.available') }}">
                <span class="fs-16 color-midnight fw-600 text-uppercase">{{ __('Book your next session') }}</span></a>
        </div>
    </div>
</div>