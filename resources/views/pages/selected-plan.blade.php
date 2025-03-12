<x-app-layout>
    <?php
        $route = $guest ? 'guest.slots.available' : 'client.slots.available';
        $params = $guest ? ['guest' => 'true'] : [];
        $date_selection_page = route($route, $params);
    ?>
    <div class="page-container">
        <div class="pick-plan-section mgb-100">
            <div class="container">
                <div class="page-title text-center mgb-50">
                    <h1 class="fs-65 color-midnight fw-700 lh-13 text-uppercase ls-04-minus font-hornsea-fc mgb-15">
                        {{ e($client_services->contract->name) }}</h1>
                </div>
                @include('partials.response')

                <div class="left-session text-center mgb-50">
                    <p class="fs-20 fw-300 color-midnight font-altivo">
                        @if (isset($client_services->message) && !blank($client_services->message))
                            {{ __($client_services->message) }}
                        @else
                            @if ($is_super_vitality)
                                {{ __('You have unlimited sessions with this plan') }}
                            @else
                                {{ __('You have') }}
                                <strong>{{ e($client_services->max_week_sessions - $client_services->booked_current_week_session) }} {{ __('of') }} {{ e($client_services->max_week_sessions) }}
                                        {{ __('sessions') }}</strong> {{ __('left in this week') }}
                            @endif
                        @endif

                    </p>
                </div>
                @if (request()->has('StartDateTime') && !blank(request()->StartDateTime))
                    @php
                        $start = \Carbon\Carbon::parse(request()->StartDateTime);
                        $end = \Carbon\Carbon::parse($start)->addMinutes(30);
                    @endphp
                    <div class="text-center mgb-30">
                        <p class="fs-20 fw-300 lh-13 color-midnight mgb-10">Time selected for your session:</p>
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <div class="calendar-lt d-flex align-items-center">
                                <div class="c-day position-relative mgr-10">
                                    <img src="{{ Vite::image('days-bg.png') }}" alt="User">
                                    <div
                                        class="date-view position-absolute top-0 left-0 d-flex align-items-center justify-content-center text-center h-100 w-100">
                                        <p>{{ $start->format('M') }}
                                            <span>{{ $start->format('j') }}</span></p>
                                    </div>
                                </div>
                                <div class="c-time">
                                    <p class="color-midnight fs-20 lh-11 fw-300">
                                        {{ $start->format('M. j, g:i A') }} to
                                        {{ $end->format('g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="check-out-btn text-center">
                    <div class="btn-wrap d-flex justify-content-center mgb-20 flex-wrap gap-5">
                        @if ($is_super_vitality || $client_services->booked_current_week_session < $client_services->max_week_sessions)
                            <form action="{{ route($guest ? 'guest.book.appointment' : 'client.book.appointment') }}"
                                method="POST">
                                @csrf
                                @if ($guest)
                                    <input type="hidden" name="guest" value="true">
                                @endif
                                <input type="hidden" name="client_contract_id"
                                    value="{{ e($client_services->contract->client_contracts->client_contract_id) }}">
                                <input type="hidden" name="ClientServiceId"
                                    value="{{ e($client_services->contract->service_id) }}">
                                <input type="hidden" name="contractId" value="{{ e($client_services->contract->id) }}">
                                <input type="hidden" name="StartDateTime" value="{{ e(request()->StartDateTime) }}">
                                <button type="submit"
                                    class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>{{ __('Confirm Booking') }}</span></button>
                            </form>
                        @endif

                        @if (!$is_super_vitality)
                            <a class="btn upgrade-plan-btn fw-700 ssm border-2x-blue color-midnight text-center border-radius-50 lh-50 text-uppercase mgb-40 open-popup"
                                data-target="upgrade-plan" href="javascript:void(0)"><span>{{ __('Upgrade plan') }}</span></a>
                        @endif
                    </div>
                    <a class="back-link fw-700 fs-20 lh-12 color-midnight text-decoration-underline" href="{{ $date_selection_page }}">{{ __('Back to dates selection') }}</a>
                </div>

            </div>
        </div>
    </div>

    
    <div style="display:none;" class="page-popup-section upgrade-plan popup" aria-modal="upgrade-plan" id="upgrade-plan">
        <div class="popup-container position-relative">
            <div class="closepop position-absolute">
                <img src="{{ Vite::image('icon-close-blue.svg') }}" alt="{{ __('Close') }}">
            </div>
            <div class="popup-title text-center">
                <h2 class="fw-700 font-hornsea-fc color-black fs-65 lh-11 mgb-30">{{ __('UPGRADE YOUR PLAN') }}</h2>
            </div>
            <div class="popup-holder">
                <form id="upgrade-form" method="POST" action="{{ route('sale.purchase.updatecontracts') }}" data-js-form>
                    @csrf
                    <div class="upgrade-plans mgb-25">
                        @php
                            $first_plan = null;
                        @endphp
                        @foreach ($contracts as $contract)
                            @if ($contract->id == $client_services->contract->id || $client_services->contract->price > $contract->price)
                                @continue
                            @endif
    
                            @php
                            if (!$first_plan):
                                $first_plan = $contract;
                            endif;
                            @endphp
                            <div class="upgrade-plan-item position-relative">
                                <input class="upgrade-selector" value="{{ $contract->id }}" type="radio" name="contractId" data-plan-type="contract" id="super_vitality" data-plan-name="{{ $contract->name }}" checked>
                                <div class="updrade-plan-block d-flex justify-content-between">
                                    <div class="upgrade-plan-lt d-flex align-items-center">
                                        <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}" alt="{{ __('Fire and Chill') }}">
                                        <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">{{ e($contract->name) }}</h3>
                                    </div>
                                    <div class="upgrade-plan-rt">
                                        <span class="color-midnight fs-40 fw-400 font-altivo"><i class="fonts-arial fst-normal">{{ __('$') }}</i>{{ e($contract->price) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
    
                        @if ($service)
                            @php
                                $checked = !$first_plan;
                                $first_plan = $first_plan ?? $service;
                            @endphp
                            <div class="upgrade-plan-item position-relative">
                                <input class="upgrade-selector" value="{{ e($service->service_id) }}" type="radio" name="service_id" data-plan-type="service" data-plan-name="{{ $service->name }}" id="per_session" @if ($checked)checked @endif>
                                <div class="updrade-plan-block d-flex justify-content-between">
                                    <div class="upgrade-plan-lt d-flex align-items-center">
                                        <img class="mgr-15" src="{{ Vite::image('fc-symbol-orange-blue.svg') }}" alt="{{ __('Fire and Chill') }}">
                                        <h3 class="fs-24 fw-700 text-uppercase font-altivo lh-12">{{ e($service->name) }}</h3>
                                    </div>
                                    <div class="upgrade-plan-rt">
                                        <span class="color-midnight fs-40 fw-400 font-altivo"><i class="fonts-arial fst-normal">{{ __('$') }}</i>{{ e($service->price) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
    
                    </div>
                    
                    @if ($credit_card)
                        <div class="mgb-20">                            
                            <div class="card-item">
                                <div class="card-item__side -front">
                                    <div class="card-item__focus" id="savedCardFocus"></div>
                                    <div class="card-item__cover">
                                        <img alt="" src="{{ Vite::image('cards-bg/'.rand(1,25).'.jpeg') }}" class="card-item__bg" />
                                    </div>
                    
                                    <div class="card-item__wrapper">
                                        <div class="card-item__top">
                                            <img src="{{ Vite::image('chip.png') }}" alt="" class="card-item__chip" />
                                            <div class="card-item__type">
                                                <img alt="{{ $credit_card->CardType }}" src="{{ Vite::image('card-type/'.strtolower($credit_card->CardType).'.png') }}" class="card-item__typeImg"
                                                    id="savedCardTypeImage" />
                                            </div>
                                        </div>
                    
                                        <label class="card-item__number" id="savedCardNumberLabel">
                                            <div id="savedCardNumber">#### #### #### {{ $credit_card->LastFour }}</div>
                                        </label>
                    
                                        <div class="card-item__content">
                                            <label class="card-item__info" id="savedCardHolderLabel">
                                                <div class="card-item__holder">Card Holder</div>
                                                <div class="card-item__name" id="savedCardHolderName">{{ $credit_card->CardHolder }}</div>
                                            </label>
                    
                                            <div class="card-item__date" id="savedCardDateLabel">
                                                <label class="card-item__dateTitle">Expires</label>
                                                <label class="card-item__dateItem" id="savedCardExpiryMonth">{{ $credit_card->ExpMonth }}</label> /
                                                <label class="card-item__dateItem" id="savedCardExpiryYear">{{ $credit_card->ExpYear }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mgt-20 text-center">
                                <a href="javascript:void(0)" class="back-link fw-700 fs-20 lh-12 color-orange text-decoration-underline open-popup" data-target="credit-card">Update card</a>
                            </div>
                        </div>
                        <input type="hidden" name="LastFour" value="{{ $credit_card->LastFour }}">
                    @endif
    
                    @guest
                        @include('partials.popups.credit-card', ['show_form' => false])
                    @endguest

                    <div class="field-block text-center mgt-20">
                        <p class="form-message"></p>
                    </div>
                    <div class="btn-wrap text-center">
                        <input type="hidden" name="StartDateTime" value="{{ request()->StartDateTime ?? '' }}">
                        <button type="submit" class="btn fw-700 ssm bg-orange hover-border color-white text-center border-radius-50 lh-50 text-uppercase mgb-40"><span>{{ __('CONFIRM'.($first_plan ? ' '.$first_plan->name : '')) }}</span></button>
                        <div class="mgt-10 mgb-10">
                            <a class="back-link fw-700 fs-20 lh-12 color-midnight text-decoration-underline" href="{{ $date_selection_page }}">{{ __('Back to dates selection') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @auth
        @include('partials.popups.credit-card', ['credit_card' => $credit_card])
    @endauth

    @section('scripts')
        @parent
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                const planSelectors = document.querySelectorAll('.upgrade-selector');
                const servicePlanSelector = document.querySelector('.upgrade-selector[data-plan-type="service"]');
                const contractPlanSelectors = document.querySelectorAll('.upgrade-selector[data-plan-type="contract"]');
                const form = document.getElementById('upgrade-form');
                const submitBtn = form.querySelector('button[type="submit"]');
                const submitBtnSpan = submitBtn.querySelector('span');
                const guest = '{{ $guest }}' === 'true';
                const selectedPlan = form.querySelector('.upgrade-selector:checked');

                function updateFormAction(planElement) {
                    
                    if (planElement.getAttribute('data-plan-type') === 'contract') {
                        servicePlanSelector.checked = false;
                        servicePlanSelector.removeAttribute('name');
                        servicePlanSelector.removeAttribute('checked');
                        contractPlanSelectors.forEach((contractPlanSelector) => {
                            contractPlanSelector.setAttribute('name', 'contractId');
                        });
                        form.action = "{{ route('sale.purchase.updatecontracts') }}";
                    } else {
                        contractPlanSelectors.forEach((contractPlanSelector) => {
                            contractPlanSelector.checked = false;
                            contractPlanSelector.removeAttribute('checked');
                            contractPlanSelector.removeAttribute('name');
                        });
                        servicePlanSelector.setAttribute('name', 'service_id');

                        form.action = "{{ route('sale.checkoutshoppingcart') }}";
                    }
                }
                

                updateFormAction(selectedPlan);

                planSelectors.forEach((planSelector) => {
                    planSelector.addEventListener('change', function() {
                        const planType = this.getAttribute('data-plan-type');
                        const planName = this.getAttribute('data-plan-name');
                        submitBtnSpan.innerText = `Confirm ${planName}`;

                        updateFormAction(this);

                    });
                });
            });
        </script>
    @endsection

</x-app-layout>