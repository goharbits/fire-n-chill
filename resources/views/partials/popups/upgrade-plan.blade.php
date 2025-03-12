<div style="display:none;" class="page-popup-section upgrade-plan popup" aria-modal="upgrade-plan" id="upgrade-plan">
    <div class="popup-container position-relative">
        <div class="closepop position-absolute">
            <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
        </div>
        <div class="popup-title text-center">
            <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('UPGRADE YOUR PLAN') }}</h2>
        </div>
        <div class="popup-holder">
            <form method="POST" action="{{ route('sale.purchase.updatecontracts') }}" data-js-form>
                @csrf
                <div class="upgrade-plans mgb-25">
                    <div class="upgrade-plan-item position-relative">
                        <input class="upgrade-selector" type="radio" name="newplan" id="super_vitality" checked>
                        <div class="updrade-plan-block d-flex justify-content-between">
                            <div class="upgrade-plan-lt d-flex align-items-center">
                                <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}" alt="{{ __('Fire and Chill') }}">
                                <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">{{ __('SUPER VITALITY') }}<br> {{ __('PLAN') }}</h3>
                            </div>
                            <div class="upgrade-plan-rt">
                                <span class="color-midnight fs-40 fw-400 font-altivo"><i class="fonts-arial fst-normal">{{ __('$') }}</i>{{ __('375') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="upgrade-plan-item position-relative">
                        <input class="upgrade-selector" type="radio" name="newplan" id="per_session">
                        <div class="updrade-plan-block d-flex justify-content-between">
                            <div class="upgrade-plan-lt d-flex align-items-center">
                                <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}" alt="{{ __('Fire and Chill') }}">
                                <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">{{ __('PER SESSION') }}</h3>
                            </div>
                            <div class="upgrade-plan-rt">
                                <span class="color-midnight fs-40 fw-400 font-altivo"><i class="fonts-arial fst-normal">{{ __('$') }}</i>{{ __('48') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="btn-wrap text-center">
                    <button type="submit" class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>{{ __('CONFIRM SUPER VITALITY PLAN') }}</span></button>
                </div>
            </form>
        </div>
    </div>
</div>